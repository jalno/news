<?php
use packages\base\Translator;
use packages\news\Authorization;
use packages\news\NewPost as Post;
use packages\userpanel;
use packages\userpanel\Date;

$this->the_header();
?>
<form id="news-post-form" action="<?php echo userpanel\url('news/edit/'.$this->post->id); ?>" method="post" enctype="multipart/form-data">
	<?php
    foreach ($this->post->files as $file) {
        $this->createField([
            'type' => 'hidden',
            'name' => 'attachment[]',
            'value' => $file->id,
        ]);
    }
?>
	<div class="row">
	    <div class="col-sm-6">
			<?php
        $this->createField([
            'name' => 'title',
            'label' => Translator::trans('news.title'),
        ]);
?>
		</div>
	</div>
	<div class="row">
	    <div class="col-md-9">
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-white panel-text">
						<div class="panel-heading">
							<i class="fa fa-edit"></i>
							<span><?php echo Translator::trans('news.text'); ?></span>
							<div class="panel-tools">
								<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
							</div>
						</div>
						<div class="panel-body">
							<?php
                $this->createField([
                    'name' => 'content',
                    'type' => 'textarea',
                    'class' => 'ckeditor',
                ]);
?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="upload-zone<?php if (!count($this->post->files)) {
					    echo ' no-file';
					} ?>" data-can-delete="<?php echo Authorization::is_accessed('files_delete') ? 'true' : 'false'; ?>">
						<input type="file" name="file" multiple="mutliple">
						<div class="panel panel-white">
							<div class="panel-heading">
								<i class="fa fa-paperclip"></i>
								<span><?php echo Translator::trans('blog.post.files'); ?></span>
								<div class="panel-tools">
									<a class="btn btn-xs btn-link btn-add tooltips" href="#" title="<?php echo Translator::trans('blog.post.files.add'); ?>"><i class="fa fa-plus"></i></a>
									<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
								</div>
							</div>
							<div class="panel-body">
								<table class="table files">
									<tbody>
									<?php
                                    foreach ($this->post->files as $file) {
                                        $this->setButtonParam('file_link', 'link', $file->url(true));
                                        ?>
									<tr data-id="<?php echo $file->id; ?>">
										<td class="center"><i class="file-icon fa <?php echo $this->getFileIcon($file); ?>"></i></td>
										<td class="col-xs-4 ltr filename"><?php echo $file->name; ?></td>
										<td class="col-xs-2"><?php echo $this->getFileSize($file); ?></td>
										<td class="col-xs-3"><input class="ltr" value="<?php echo $file->url(true); ?>" type="url"></td>
										<td class="center"><?php echo $this->genButtons(); ?></td>
									</tr>
									<?php
                                    }
?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="no-file">
							<i class="fa fa-cloud-upload"></i><?php echo Translator::trans('blog.post.files.drag&drop'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-white panel-details">
				<div class="panel-heading">
					<i class="fa fa-clock-o"></i>
					<span><?php echo Translator::trans('news.post.details'); ?></span>
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
					</div>
				</div>
				<div class="panel-body">
					<input type="hidden" name="author" value="<?php echo $this->getDataForm('author'); ?>">
					<input type="hidden" name="category" value="" class="">
					<?php
                    $this->createField([
                        'name' => 'date',
                        'label' => Translator::trans('news.date'),
                        'value' => Date::format('Y/m/d H:i', Date::time()),
                        'class' => 'form-control ltr',
                    ]);
$this->createField([
    'type' => 'select',
    'name' => 'status',
    'label' => Translator::trans('news.status'),
    'options' => [
        [
            'title' => Translator::trans('new.published'),
            'value' => Post::published,
        ],
        [
            'title' => Translator::trans('new.unpublished'),
            'value' => Post::unpublished,
        ],
    ],
]);
$this->createField([
    'name' => 'author_name',
    'label' => Translator::trans('news.author'),
]);
?>
				</div>
				<div class="panel-footer">
					<button type="submit" class="btn btn-success btn-block"><i class="fa fa-floppy-o"></i> <?php echo Translator::trans('news.post.save'); ?></button>
				</div>
			</div>
			<div class="panel panel-white panel-description">
				<div class="panel-heading">
					<i class="fa fa-file-text-o"></i>
					<span><?php echo Translator::trans('news.description'); ?></span>
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
					</div>
				</div>
				<div class="panel-body">
					<?php
$this->createField([
    'type' => 'textarea',
    'name' => 'description',
    'rows' => 4,
]);
?>
				</div>
			</div>
			<div class="panel panel-white panel-thumbnail">
				<div class="panel-heading">
					<i class="fa fa-picture-o"></i>
					<span><?php echo Translator::trans('news.post.thumbnail'); ?></span>
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#"></a>
					</div>
				</div>
				<div class="panel-body">
					<div class="post-thumbnail-image avatarPreview">
						<img src="<?php echo $this->getImage(); ?>" class="preview img-responsive">
						<input name="image" type="file">
						<div class="button-group">
							<button type="button" class="btn btn-teal btn-sm btn-upload"><i class="fa fa-pencil"></i></button>
							<button type="button" class="btn btn-bricky btn-sm btn-remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<?php
    $this->the_footer();
