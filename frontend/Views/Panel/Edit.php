<?php

namespace themes\clipone\Views\News\Panel;

use packages\base\Frontend\Theme;
use packages\base\Options;
use packages\base\Packages;
use packages\base\Translator;
use packages\news\Authorization;
use packages\news\File;
use packages\news\Views\Panel\Edit as NewEdit;
use packages\userpanel\User;
use themes\clipone\Navigation;
use themes\clipone\Views\FormTrait;
use themes\clipone\Views\ListTrait;
use themes\clipone\ViewTrait;

class Edit extends NewEdit
{
    use ViewTrait;
    use ListTrait;
    use FormTrait;
    protected $post;

    public function __beforeLoad()
    {
        $this->post = $this->getPost();
        $this->setTitle([
            t('news'),
            t('edit'),
        ]);
        Navigation::active('news/index');
        $this->addJSFile(Theme::url('assets/plugins/ckeditor/ckeditor.js'));
        $this->setUserFormData();
        $this->setButtons();
    }

    protected function getImage()
    {
        $newspackage = Packages::package('news');

        return $newspackage->url($this->post->image ? $this->post->image : Options::get('packages.news.defaultimage'));
    }

    protected function setUserFormData()
    {
        if ($author = $this->getDataForm('author')) {
            if ($author = User::byId($author)) {
                $this->setDataForm($author->getFullName(), 'author_name');
            }
        }
    }

    public function setButtons()
    {
        $this->setButton('file_link', true, [
            'title' => t('news.post.files.link'),
            'icon' => 'fa fa-link',
            'classes' => ['btn', 'btn-xs', 'btn-info'],
        ]);
        $this->setButton('file_delete', Authorization::is_accessed('files_delete'), [
            'title' => t('delete'),
            'icon' => 'fa fa-times',
            'classes' => ['btn', 'btn-xs', 'btn-bricky', 'btn-delete'],
        ]);
    }

    public function getFileSize(File $file)
    {
        if ($file->size < 1024) {
            return t('news.files.size.byBytes', ['bytes' => $file->size]);
        } elseif ($file->size < 1024 * 1024) {
            return t('news.files.size.byKB', ['kb' => round($file->size / 1024)]);
        } elseif ($file->size < 1024 * 1024 * 1024) {
            return t('news.files.size.byMB', ['mb' => round($file->size / 1024 / 1024)]);
        } elseif ($file->size < 1024 * 1024 * 1024 * 1024) {
            return t('news.files.size.byGB', ['gb' => round($file->size / 1024 / 1024 / 1024)]);
        } else {
            return t('news.files.size.byTB', ['tb' => round($file->size / 1024 / 1024 / 1024 / 1024)]);
        }
    }

    public function getFileIcon(File $file)
    {
        $ext = substr($file->name, -strrpos($file->name, '.') + 1);
        switch ($ext) {
            case 'html':
            case 'css':
            case 'xml':
            case 'js':
            case 'atom':
            case 'rss':
            case 'json':
            case 'perl':
            case 'xhtml':
                return 'fa fa-file-code-o';
            case 'gif':
            case 'png':
            case 'jpeg':
            case 'tiff':
            case 'ico':
            case 'bmp':
            case 'svg':
            case 'webp':
                return 'fa fa-file-image-o';

            case 'txt':
            case 'rtf':
                return 'fa fa-file-text-o';
            case 'doc':
            case 'docx':
                return 'fa fa-file-word-o';
            case 'xls':
            case 'xlsx':
                return 'fa fa-file-excel-o';
            case 'ppt':
            case 'pptx':
                return 'fa fa-file-powerpoint-o';
            case 'pdf':
                return 'fa fa-file-pdf-o';
            case 'zip':
            case 'rar':
            case '7z':
                return 'fa fa-file-archive-o';
            case '3gpp':
            case 'mp4':
            case 'mpeg':
            case 'webm':
            case 'flv':
            case 'm4v':
            case 'mng':
            case 'wmv':
                return 'fa fa-file-video-o';
            case 'mp3':
            case 'ogg':
            case 'm4a':
                return 'fa fa-file-audio-o';
            default:
                return 'fa fa-file-o';
        }
    }
}
