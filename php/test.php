<?php
	require('./twilio-php-master/Services/Twilio.php'); 
	$config = require('../../config_offline.php'); 

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

    $twilioAPI = $config["twilioAPI"];
    $accountSID = $config["accountSID"];
    $authToken = $config["authToken"];

    $wolframID = $config["wolframID"];

    $message = $_POST["Body"];
    $term = "";
    $plainText = "";
	$searchSuccess = true; 
	$tooLong = false;

    //search type
    $messageSplit = explode(" ", $message);
    if(strtolower($messageSplit[0]) == "wiki")
    {
         //search user term
        $xml=simplexml_load_file("https://en.wikipedia.org/w/api.php?action=query&format=xml&list=search&srsearch=" . substr(strstr($message, ' '), 1));
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
            if(strlen($plainText) >= 800)
            {
                $tooLong = true;
            }
        }
    }
    else if(strtolower($messageSplit[0]) == "wolfram")
    { 
        $xml = simplexml_load_file("http://api.wolframalpha.com/v2/query?input=" . substr(strstr($message, ' '), 1) . "&appid=" . $wolframID);
        foreach($xml->pod as $pod)
        {
            $plainText .= $pod["title"] . ": " . $pod->subpod->plaintext . "\n";
        } 
        if(strlen($plainText) >= 800)
        {
            $tooLong = true;
        }
        $plainText = htmlspecialchars($plainText);
    }

   

?>
<Response>
	<?php
		if ($tooLong) 
		{
			echo "<Message>" . substr($plainText, 0, 797) . "...</Message>";
		}
		else
		{
		 	echo "<Message>" . $plainText . "</Message>"; 
		}
	?>
</Response>