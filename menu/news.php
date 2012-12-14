<?php
function ttstore_news() {
	global $foldercache;
	$news_dir = $foldercache.'news.xml';
	if (!file_exists($news_dir)) {
		$news_dir = 'http://wpaffiliatefeed.com/category/news/feed/'; 
		$news_rec = wp_remote_get($news_dir);
		$news = $news_rec['body'];
	} else {
		$news = file_get_contents($news_dir);
	}
	$news = simplexml_load_string($news);
	echo "<ul>";
		foreach($news->channel->item as $newsmsg) // loop through our items
		{
			echo "<li><strong><a href=\"".$newsmsg->link."\">".$newsmsg->title."</a></strong><br><strong>Posted: ".date("d M Y",strtotime($newsmsg->pubDate))."</strong><br>".$newsmsg->description."</li>";
		}
	echo "</ul>";
}
?>