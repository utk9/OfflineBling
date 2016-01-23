<?php
	require('./twilio-php-master/Services/Twilio.php'); 
	$config = require('../../config_offline.php'); 

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

    $twilioAPI = $config["twilioAPI"];
    $accountSID = $config["accountSID"];
    $authToken = $config["authToken"];

    $message = $_POST["Body"];
    $term = "";
	$searchSuccess = true;
	$tooLong = false;

    //search user term
    $xml=simplexml_load_file("https://en.wikipedia.org/w/api.php?action=query&format=xml&list=search&srsearch=" . $message);
    $search=$xml->query->search;
    if(count($search->children()) == 0)
    {
    	$suggestion = $xml->query->searchinfo;
    	if(isset($suggestion["suggestion"]))
    	{
    		$xml=simplexml_load_file("https://en.wikipedia.org/w/api.php?action=query&format=xml&list=search&srsearch=" . $suggestion["suggestion"]);
    		$search=$xml->query->search;
    		if(count($search->children()) == 0)
    		{
	    		$plainText = "Sorry, your search term returned no results. Please try another term.";
	    		$searchSuccess = false;
    		}
    		else
    		{
    			$term = $search->p[0]["title"];
    		}
    	}
    	else
    	{
    		$plainText = "Sorry, your search term returned no results. Please try another term.";
    		$searchSuccess = false;
    	}
    }
    else
    {
		$term = $search->p[0]["title"];
    }


    //parse 
    if($searchSuccess)
    {
    	$xml = simplexml_load_file("https://en.wikipedia.org/w/api.php?format=xml&action=query&prop=extracts&exintro=&explaintext=&titles=" . $term);
    	$plainText = $xml->query->pages->page->extract;
    	if(strlen($plainText) >= 1600)
    	{
    		$tooLong = true;
    	}
	}

?>
<Response>
	<?php
		if ($tooLong) 
		{
			echo "<Message>" . substr($plainText, 0, 1597) . "...</Message>";
		}
		else
		{
		 	echo "<Message>" . $plainText . "</Message>"; 
		}
	?>
</Response>