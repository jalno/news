<?php

namespace packages\news\Controllers;

use packages\base;
use packages\news\Controller;
use packages\news\NewPost;

class RSS extends Controller
{
    protected $items = [];

    public function build()
    {
        $this->response->setMimeType('application/rss+xml');
        $code = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $code .= '<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>'."\n";
        $code .= "\t<channel>\n";
        $code .= "\t\t<title>News</title>\n";
        $code .= "\t\t<atom:link href=\"".base\url('news/rss', null, [], true)."\" rel=\"self\" type=\"application/rss+xml\" />\n";
        $code .= "\t\t<link>".base\url('news', null, [], true)."</link>\n";

        $new = new NewPost();
        $new->orderBy('date', 'DESC');
        $new->where('status', NewPost::published);
        $news = $new->get(20);

        foreach ($news as $item) {
            $code .= "\t\t<item>\n";
            $code .= "\t\t\t<title>".$item->title."</title>\n";
            $code .= "\t\t\t<link>".base\url('news/view/'.$item->id, [], true)."</link>\n";
            $code .= "\t\t\t<comments>".base\url('news/view/'.$item->id, [], true)."#comment</comments>\n";
            $code .= "\t\t\t<pubDate>".date('r', $item->date)."</pubDate>\n";
            $code .= "\t\t\t<dc:creator><![CDATA[".$item->author->name."]]></dc:creator>\n";
            $code .= "\t\t\t<description><![CDATA[".$item->description."]]></description>\n";
            $code .= "\t\t\t<content:encoded><![CDATA[".$item->content."]]></content:encoded>\n";
            $code .= "\t\t</item>\n";
        }
        $code .= "\t</channel>";
        $code .= '</rss>';
        $this->response->rawOutput($code);

        return $this->response;
    }
}
