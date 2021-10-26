<?php
    require_once 'db_creds.inc';
    $pdo = new pdo(K_CONNECTION_STRING, K_USERNAME, K_PASSWORD);
    
    header("Content-Type: text/html; charset=utf-8");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    function getCheckingValue() {
        global $pdo;
        $sql = "SELECT checking FROM accounts";
        $resultC = $pdo->query($sql)->fetch();
        echo $resultC["checking"];
    }

    function getSavingsValue() {
        global $pdo;
        $sql = "SELECT savings FROM accounts";
        $resultS = $pdo->query($sql)->fetch();
        echo $resultS["savings"];
    }

    function depositChecking($a) {
        global $pdo;
        $sql = "UPDATE accounts SET checking = checking + ?";
        $prepared = $pdo->prepare($sql);
 	    $prepared->execute([$a]);
    }

    function depositSavings($a) {
        global $pdo;
        $sql = "UPDATE accounts SET savings = savings + ?";
        $prepared = $pdo->prepare($sql);
 	    $prepared->execute([$a]);
    }

    function transferChecking($a) {
        global $pdo;
        $sql = "SELECT checking FROM accounts";
        $transResultC = $pdo->query($sql)->fetch();
        if ($a <= $transResultC["checking"]) {
            $sql = "UPDATE accounts SET savings = savings + ?, checking = checking - ?";
            $prepared = $pdo->prepare($sql);
 	        $prepared->execute([$a, $a]);
        }
        else {
            echo "<p style='color: red; font-size: 20px'> ***Checking would have a negative balance </p>";
        }
    }

    function transferSavings($a) {
        global $pdo;
        $sql = "SELECT savings FROM accounts";
        $transResultS = $pdo->query($sql)->fetch();
        if ($a <= $transResultS["savings"]) {
            $sql = "UPDATE accounts SET savings = savings - ?, checking = checking + ?";
            $prepared = $pdo->prepare($sql);
 	        $prepared->execute([$a, $a]);
        }
        else {
            echo "<p style='color: red; font-size: 20px'> ***Savings would have a negative balance </p>";
        }
    }

    function resetAcc() {
        global $pdo;
        $sql = "UPDATE accounts SET savings = ?, checking = ?";
        $prepared = $pdo->prepare($sql);
 	    $prepared->execute([1000, 100]);
    }
    
    if (isset($_POST['depositC'])) {
        $depC = $_POST['depositC'];
        if ($depC < 0) {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a positive number </p>";
        }
        else if (is_numeric($depC)) {
            depositChecking($depC);
        }
        else {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a number </p>";
        }
    }

    if (isset($_POST['depositS'])) {
        $depS = $_POST['depositS'];
        if ($depS < 0) {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a positive number </p>";
        }
        else if (is_numeric($depS)) {
            depositSavings($depS);
        }
        else {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a number </p>";
        }
    }

    if (isset($_POST['transferC'])) {
        $transC = $_POST['transferC'];
        if ($transC < 0) {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a positive number </p>";
        }
        else if (is_numeric($transC)) {
            transferChecking($transC);
        }
        else {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a number </p>";
        }
    }

    if (isset($_POST['transferS'])) {
        $transS = $_POST['transferS'];
        if ($transS < 0) {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a positive number </p>";
        }
        else if (is_numeric($transS)) {
            transferSavings($transS);
        }
        else {
            echo "<p style='color: red; font-size: 20px'> ***Input must be a number </p>";
        }
    }

    if (isset($_POST['reset'])) {
        resetAcc();
    }
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset = "UTF-8">
    <title> First Bank of HTML </title>
</head>
<body> 
    <h1> Welcome to the First Bank of HTML &#8482; </h1>
    <p> 
        Where all our clients are served! 
        <br>
        <span style="background-color: yellow;"> This website is under construction, as is our bank. </span>
    </p>
    <h2> Services Offered </h2>
    <ol>
        <li> 
            Current account information
            <br>
            <table style="border: 2px; border-style: solid; border-color: blue">
                <tbody>
                    <tr>
                        <th> checking </th>
                        <th> savings </th>
                    </tr>
                    <tr>
                        <td> <?= getCheckingValue(); ?> </td>
                        <td> <?= getSavingsValue(); ?> </td>
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            <form method = "post" action = "">
            Deposit money into checking
            <input type="text" name="depositC">
            <input type="submit" value="Submit">
            </form>
        </li>
        <li> 
            <form method = "post" action = "">
            Deposit money into savings 
            <input type="text" name="depositS">
            <input type="submit" value="Submit">
            </form>
        </li>
        <li>  
            <form method = "post" action = "">
            Transfer money from checking into savings
            <input type="text" name="transferC">
            <input type="submit" value="Submit">
            </form>
        </li>
        <li>  
            <form method = "post" action = "">
            Transfer money from savings into checking
            <input type="text" name="transferS">
            <input type="submit" value="Submit">
            </form>
        </li>
        <li>
            <form method = "post" action = "">
            Reset account to initial values
            <input type="submit" name="reset" value="Start Fresh">
            </form>
        </li>
    </ol>
</body>
</html>
<!-- vim:filetype=php:
-->