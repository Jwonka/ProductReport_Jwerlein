<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Minisworld Product Report - Jwerlein</title>
        
        <link href="supportData/css/prodRep.css" rel="stylesheet">
        
    </head>
    <body><?php

        function sanitizeString($field) {
            return filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
        }

        $submitPressed = sanitizeString('go');
        

        $myForm = <<<MYFORM
        <div class="myForm">

            <h2>Minisworld Company Product Report</h2>

            <form action="$_SERVER[PHP_SELF]" method="post">

                <label for="fName">First Name:</label>
                <input type="text" name="fName" id="fName" size="20">
                <br><br>

                <label for="lName">Last Name:</label>
                <input type="text" name="lName" id="lName" size="20">
                <br><br>

                <label for="choices">Product Report Choices</label><br>
                <select name="choices[]" multiple>
                    <option value="figs" selected="yes">Painted Figures</option>
                    <option value="scenery">Town Square Scenery</option>
                    <option value="supplies">Misc Scenic Supplies</option>
                </select>
                <br><br>

                <input type="submit" name="go" id="go" value="Generate Product Report">

            </form>

        </div>
MYFORM;

        //
        // Decide if we should display the form OR the resulting product report page
        //
        if (!isset($submitPressed)) {   // form was NOT submitted, so display form
            // display the form here
            echo $myForm;
        }
        else {   // else form was submitted, process and display selected product's reports

            $fName = sanitizeString('fName');
            $lName = sanitizeString('lName');
            
            if (!isset($fName)) {
                $fName = "";
            }
            
            if (!isset($lName)) {
                $lName = "";
            }
            
            // greet user here as an <h2> using both first and last name from form
            // hint: you will need to use a span tag with an id value already set up in 
            // your .css file
            ?> 
        <h2>Welcome <span id="greeting"><?= $fName . " " . $lName ?></span></h2><?php 
            
            // Step through all selected product categories - no change needed here
            if (isset($_POST['choices'])) {
                $choices = $_POST['choices'];   // shortcut
            }
            
            if (!isset($choices)) {
                print "\t<h2>No product categories were chosen</h2>\n\n";
                
                // now, redisplay the form
                echo $myForm;
                
            }
            else {  // user selected at least one category
                
                $productCount = array();
                
                // associate report name with our categories
                $reportName["figs"] = "Painted Figures";
                $reportName["scenery"] = "Town Square Scenery";
                $reportName["supplies"] = "Misc Scenic Supplies";
                
                // Assign $fp the result of opening the file
                // containing category descriptive names.
                $fp = fopen("supportData/prodCategories.txt", "r");

                // In a single loop, read each line from $fp,
                // storing the result in $category. Remove any
                // trailing spaces from $category. If the line 
                // has valid data, use the category name as
                // a key for the $productCount associative array
                // and assign it a value of zero.
                while (!feof($fp)) {
                    $category = rtrim(fgets($fp)); 

                    if ($category != "") {
                        $productCount[$category] = 0;
                    }
                    //print($category);
                }
                //print_r($productCount);
                //echo "<h3> $productCount[$category] is the category<h3>";

                // Set default timezone
                date_default_timezone_set('America/Chicago');
                
                // loop through the selected categories from the form and produce a heading
                // and a table for each of them - use a foreach loop and store the value
                // from each element of the selected choices from the form in a variable 
                // named $inputFileName.
                foreach ($choices as $selectedCategory => $inputFileName) {

                    //print($category);
                    //print_r($productCount);
                    // generate report header for this category using your $reportName
                    // array and generate the current year (4 digits) using PHP's date() function.
                    // Replace the areas in the output below with the ***'s.
                    $currentYear = date("Y");

                    print "\n\t\t<h2>" . $reportName[$inputFileName] . " Product Report for " . $currentYear . "</h2>\n\n";

                    // obtain product info for all products in this selected category
                    // and display info for each product on its row of a table
                    print "\t<div class=\"ieCenter\">\n\n";
                    print "\t\t<table>\n";

                    // open the input file associated with this selected category and store
                    // returned file pointer in $fp
                     $fp = fopen("supportData/$inputFileName", "r");
                    
                    // Read each line from the opened file using a while loop until you
                    // reach end-of-file storing each line in a variable named $product. 
                    // Be sure to remove any trailing \n character.
                    // while not EOF
                    while (!feof($fp)) {
                        // read next line
                        $product = rtrim(fgets($fp));
                        //print_r($product);
                        if ($product != "") {  // we have a valid product
 
                            // increment this category's product count in the $productCount array
                            $productCount[$reportName[$inputFileName]]++;
                            //print_r($productCount);
                            // break up the line into its individual field values storing 
                            // them from left-to-right in variables named: $productID, $numSold, 
                            // $price, $numAvail, $cost, $desc, and $imgPath.
                            list($productID, $numSold, $price, $numAvail, $cost, $desc, $imgPath) = explode(":", $product);
                            //print_r($product);
                            //print $imgPath;
                            // generate table row for this product's info by juumping out
                            // of PHP delimiters...
                            ?>
                            
            <tr>
                
                <!-- first column -->
                <td class="diff">
                    
                    <blockquote>
                        Item description:<br>
                        <strong><?= $desc ?></strong>
                        <br><br>
                        Part Number = <strong><?= $productID ?></strong>
                    </blockquote>
                    
                </td>
                
                <!-- second column -->  
                <td>

                    <div id="fadeImage">
                        <img src="<?= "supportData/" . $imgPath ?>" id="img" alt="<?= $desc ?>">
                    </div>

                </td>
                
                <!-- third column -->
                <td class="diff">
                    
                    <p>
                        Price: <strong><?= $price ?></strong><br>
                        Number Sold: <strong><?= $numSold ?></strong><br>
                        Total Revenue Generated: <strong><?= $numSold * $price?></strong><br><br>
                        Cost: <strong><?= $cost ?></strong><br>
                        Left in Inventory: <strong><?= $numAvail ?></strong><br>
                        Cost of Inventory: <strong><?= $numAvail * $cost?></strong><br>
                    </p> 
                    
                </td>
                
            </tr>
                    <?php 
                        }  // end if we have a valid product

                    }  // end while not EOF
                    
                    print "\n\t\t</table>\n\n";
                    print "\t</div>\n\n";
                    
                }    // end foreach selected category
                ?>
        <h3><strong>Product count by Category</strong></h3>
            <ul><?php   
                // Display a bulleted list of product categories showing
                // number of products in each selected category.  Use a foreach
                // loop to step through this associative array obtaining both the 
                // key ($categoryName) and value ($numProducts) for each element in the array.

                foreach ($productCount as $categoryName => $numProducts) {
                    //print_r($productCount);
                    //print($category);
                    //print($categoryName);
                    //print($productCount[$categoryName]);
                    
                echo "\n\t\t\t\t<li>" . $categoryName . ": " . $numProducts . "</li>";
                }
                ?> 
            </ul><?php 
            }  // end else user selected at least one category
        }  // end else form was submitted      
        ?> 
        <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>
        <script src="supportData/js/fadePic.js"></script>
    </body>
</html>
