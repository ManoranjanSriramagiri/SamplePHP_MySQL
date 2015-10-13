<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<h1>Query Result</h1>

<?php

$servername = "127.0.0.1:3306";
$username = "root";
$password = "";


class Result
    {
        private $sellerId;
        private $noOfItemsSold;
        private $noOfItemsPurchased;
        
        public function getSellerID()     { return $this->sellerID; }
        public function getnoOfItemsSold()  { return $this->noOfItemsSold; }
        public function getnoOfItemsPurchased()   { return $this->noOfItemsPurchased; }
        
    }


$login = filter_input(INPUT_POST, "login");
$first = filter_input(INPUT_POST, "fname");
$last  = filter_input(INPUT_POST, "lname");
$email = filter_input(INPUT_POST, "email");
$password  = filter_input(INPUT_POST, "pass");
$security = filter_input(INPUT_POST, "security");
$city = filter_input(INPUT_POST,"city");
$gender = filter_input(INPUT_POST,"radio1");
$itemsold = filter_input(INPUT_POST,"itemsold");
$itempurchased = filter_input(INPUT_POST,"itempurchased");
try {
    
    
    
    $conn = new PDO("mysql:host=$servername;dbname=test", $username, "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
   $sql = "INSERT INTO users (loginId,pWord,securityAnswer,FirstName,LastName,Gender,Address,Email)
    VALUES ('$login','$password','$security','$first','$last','$gender','$city','$email')";

        $sql1 = "INSERT INTO seller (sellerId,noOfItemsSold)
    VALUES ('$login', '$itemsold')";

    $sql2 = "INSERT INTO bidder (bidderId,noOfItemsPurchased)
    VALUES ('$login', '$itempurchased')";


        $conn->exec($sql);
        $conn->exec($sql1);
        $conn->exec($sql2);

    $query = "select S.sellerID , S.noOfItemsSold , B.noOfItemsPurchased 
              from Seller S inner join bidder  B on B.bidderId=S.sellerId
               group by S.sellerID, S.noOfItemsSold, B.noOfItemsPurchased
                having S.noOfItemsSold > B.noOfItemsPurchased";
   
    
    $ps = $conn->prepare($query);
    $ps->execute();
    $ps->setFetchMode(PDO::FETCH_CLASS, "Result");

  
    print "<table border='1'>\n";
 $result = $conn->query($query);
 $row = $result->fetch(PDO::FETCH_ASSOC);

   print "<tr>\n";
    foreach ($row as $field => $value) {
        print "<th>$field</th>\n";
    }
    print "</tr>\n";


    while ($Result = $ps->fetch()) {
        print "        <tr>\n";
        print "            <td>" . $Result->getSellerID()     . "</td>\n";
        print "            <td>" . $Result->getnoOfItemsSold()  . "</td>\n";
        print "            <td>" . $Result->getnoOfItemsPurchased()   . "</td>\n";
        print "        </tr>\n";
    }
    print "</table>\n";


    
    
    
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

?>

</body>
</html>
