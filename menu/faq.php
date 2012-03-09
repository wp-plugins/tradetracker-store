<?php
function ttstore_faq() {
	global $foldercache;
	$faq_dir = $foldercache.'faq.xml';
	if (!file_exists($faq_dir)) {
		   $faq_dir = 'http://wpaffiliatefeed.com/tradetracker-store/faq.xml'; 
	} 
	$faq = file_get_contents($faq_dir);
	$faq = simplexml_load_string($faq);
	echo "<ul>";

	foreach($faq as $faqs) // loop through our items
	{
		if(!isset($faqcategory)){
			$faqcategory = $faqs->faqcategory;
			echo "<li><strong>$faqcategory</strong></li>";
			echo "<li><a href=\"".$faqs->faqadres."\" target=\"_blank\">".$faqs->faqnaam."</a></li>";
		} else if($faqs->faqcategory != "".$faqcategory.""){
			$faqcategory = $faqs->faqcategory;
			echo "<li><strong>$faqcategory</strong></li>";
			echo "<li><a href=\"".$faqs->faqadres."\" target=\"_blank\">".$faqs->faqnaam."</a></li>";
		} else {
			echo "<li><a href=\"".$faqs->faqadres."\" target=\"_blank\">".$faqs->faqnaam."</a></li>";
		}	
	}
	echo "</ul>";
}
?>