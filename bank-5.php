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

    function withdrawChecking($a) {
        global $pdo;
        $sql = "SELECT checking FROM accounts";
        $withResultC = $pdo->query($sql)->fetch();
        if ($a <= $withResultC["checking"]) {
            $sql = "UPDATE accounts SET checking = checking - ?";
            $prepared = $pdo->prepare($sql);
 	        $prepared->execute([$a]);
        }
    }

    function withdrawSavings($a) {
        global $pdo;
        $sql = "SELECT savings FROM accounts";
        $withResultS = $pdo->query($sql)->fetch();
        if ($a <= $withResultS["savings"]) {
            $sql = "UPDATE accounts SET savings = savings - ?";
            $prepared = $pdo->prepare($sql);
 	        $prepared->execute([$a]);
        }
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
    }

    function resetAcc() {
        global $pdo;
        $sql = "UPDATE accounts SET savings = ?, checking = ?";
        $prepared = $pdo->prepare($sql);
 	    $prepared->execute([1000, 100]);
    }

    if (isset($_POST['depositC'])) {
        $depC = $_POST['depositC'];
        if (is_numeric($depC)) {
            $depC = (int)$depC;
            if ($depC > 0) {
                depositChecking($depC);
            }
        }
    }

    if (isset($_POST['depositS'])) {
        $depS = $_POST['depositS'];
        if (is_numeric($depS)) {
            $depS = (int)$depS;
            if ($depS > 0) {
                depositSavings($depS);
            }
        }
    }

    if (isset($_POST['withdrawC'])) {
        $withC = $_POST['withdrawC'];
        if (is_numeric($withC)) {
            $withC = (int)$withC;
            if ($withC > 0) {
                withdrawChecking($withC);
            }
        }
    }

    if (isset($_POST['withdrawS'])) {
        $withS = $_POST['withdrawS'];
        if (is_numeric($withS)) {
            $withS = (int)$withS;
            if ($withS > 0) {
                withdrawSavings($withS);
            }
        }
    }

    if (isset($_POST['transferC'])) {
        $transC = $_POST['transferC'];
        if (is_numeric($transC)) {
            $transC = (int)$transC;
            if ($transC > 0) {
                transferChecking($transC);
            }
        }
    }

    if (isset($_POST['transferS'])) {
        $transS = $_POST['transferS'];
        if (is_numeric($transS)) {
            $transS = (int)$transS;
            if ($transS > 0) {
                transferSavings($transS);
            }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" 
    integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" 
    integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" 
    integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    
    <title> First Bank of HTML </title>
    <svg height="200", width="200">
        <rect x="25%" y="25%" width="50%" height="50%" style="fill:#FA8072"></rect>
        <ellipse cx="50%" cy="50%" rx="12.5%" ry="12.5%" style="fill:#33BBFF"></ellipse>
        <polygon points="250,100 50,20 300,100" style="fill:black"></polygon>
        <polygon points="100,300 50,200 20,60" style="fill:black"></polygon>
    </svg>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        //extra work: easter egg
        //-----------------------
        function realWord() {
            $("#change").text("But we have NO CUSTOMERS");
        };
        //-----------------------
        $(document).ready(function() {
            $("#change").css("background-color", "yellow");
            $("table").css({
                "border": "2px",
                "border-style": "solid",
                "border-color": "blue"
            });

            //let sampleData = [];
            //sampleData.push({"type": "hello", "source": "yo", "destination": "lol", "amount": "lmao"});
            //sampleData.push({"type": "qwee", "source": "rat", "destination": "llolol", "amount": "robot"});
            function createTable(transactions) {
                $("#recordTable").DataTable().clear().destroy();
                $("#recordTable").DataTable({
                    data: transactions,
                    columns: [
                        {data: "type"},
                        {data: "source"},
                        {data: "destination"},
                        {data: "amount"}
                    ]
                });
            }
            //createTable(sampleData);

            let openRequest = indexedDB.open("transactions", 1);
            openRequest.onsuccess = function(event) {
                ultBankDB = openRequest.result;
                console.log("Database loaded");
            }

            openRequest.onupgradeneeded = function(event) {
                let objectStore = event.currentTarget.result.createObjectStore("transRecord", {keypath: "id", autoIncrement: "true"});
                objectStore.createIndex("type", "type", { unique: false });
                objectStore.createIndex("source", "source", { unique: false });
                objectStore.createIndex("destination", "destination", { unique: false });
                objectStore.createIndex("amount", "amount", { unique: false });
            }

            function addEntry(newType, newSource, newDestination, newAmount) {
                let transaction = ultBankDB.transaction("transRecord", "readwrite");
                let history = transaction.objectStore("transRecord");
                let historyJSON = {"type": newType, "source": newSource, "destination": newDestination, "amount": newAmount}
                let request = history.add(historyJSON)

                request.onsuccess = function() {
                    console.log("Entry added");
                }
            }

            function displayDB() {
                let array = [];
                let transaction = ultBankDB.transaction("transRecord");
                let history = transaction.objectStore("transRecord");
                let request = history.openCursor();
                request.onsuccess = function() {
                    let cursor = request.result;
                    if (cursor) {
                        let newType = cursor.value.type;
                        let newSource = cursor.value.source;
                        let newDestination = cursor.value.destination;
                        let newAmount = cursor.value.amount;
                        array.push({"type": newType, "source": newSource, "destination": newDestination, "amount": newAmount})
                        cursor.continue();
                    }
                    createTable(array);
                }
            }
    
            $("#depCsub").click(function() {
                let depositC = $("#depositC").val();
                if ($.isNumeric(depositC)) {
                    depositC = parseInt(depositC);
                    if (depositC > 0) {
                        $.ajax({
                            type: "post",
                            data: {"depositC": depositC},
                            dataType: "html",
                            success: function(response) {
                                let checkingVal = $(response).find("#checking");
                                $("#checking").text(checkingVal.text());
                                let savingsVal = $(response).find("#savings");
                                $("#savings").text(savingsVal.text());
                                addEntry("deposit", "external", "checking", depositC);
                                displayDB();
                            }
                        });
                    }
                    else {
                        alert("Input must be a positive number");
                    }
                }
                else {
                    alert("Input must be a number");
                }
            });

            $("#depSsub").click(function() {
                let depositS = $("#depositS").val();
                if($.isNumeric(depositS)) {
                    depositS = parseInt(depositS);
                    if (depositS > 0) {
                        $.ajax({
                            type: "post",
                            data: {"depositS": depositS},
                            dataType: "html",
                            success: function(response) {
                                let checkingVal = $(response).find("#checking");
                                $("#checking").text(checkingVal.text());
                                let savingsVal = $(response).find("#savings");
                                $("#savings").text(savingsVal.text());
                                addEntry("deposit", "external", "savings", depositS);
                                displayDB();
                            }
                        });
                    }
                    else {
                        alert("Input must be a positive number");
                    }
                }
                else {
                    alert("Input must be a number");
                }
            });

            $("#withCsub").click(function() {
                let withdrawC = $("#withdrawC").val();
                if ($.isNumeric(withdrawC)) {
                    withdrawC = parseInt(withdrawC);
                    if (withdrawC > 0) {
                        $.ajax({
                            type: "post",
                            dataType: "html",
                            success: function(response) {
                                let testChecking = $(response).find("#checking");
                                testChecking = testChecking.text().trim();
                                testChecking = parseInt(testChecking);
                                if (withdrawC <= testChecking) {
                                    $.ajax({
                                        type: "post",
                                        data: {"withdrawC": withdrawC},
                                        dataType: "html",
                                        success: function(response) {
                                            let checkingVal = $(response).find("#checking");
                                            $("#checking").text(checkingVal.text());
                                            let savingsVal = $(response).find("#savings");
                                            $("#savings").text(savingsVal.text());
                                            addEntry("withdrawal", "checking", "external", withdrawC);
                                            displayDB();
                                        }
                                    });
                                }
                                else {
                                    alert("Checking would have a negative number");
                                }
                            }
                        });
                    }
                    else {
                        alert("Input must be a positive number");
                    }
                }
                else {
                    alert("Input must be a number");
                }
            });

            $("#withSsub").click(function() {
                let withdrawS = $("#withdrawS").val();
                if ($.isNumeric(withdrawS)) {
                    withdrawS = parseInt(withdrawS);
                    if (withdrawS > 0) {
                        $.ajax({
                            type: "post",
                            dataType: "html",
                            success: function(response) {
                                let testSavings = $(response).find("#savings");
                                testSavings = testSavings.text().trim();
                                testSavings = parseInt(testSavings);
                                if (withdrawS <= testSavings) {
                                    $.ajax({
                                        type: "post",
                                        data: {"withdrawS": withdrawS},
                                        dataType: "html",
                                        success: function(response) {
                                            let checkingVal = $(response).find("#checking");
                                            $("#checking").text(checkingVal.text());
                                            let savingsVal = $(response).find("#savings");
                                            $("#savings").text(savingsVal.text());
                                            addEntry("withdrawal", "savings", "external", withdrawS);
                                            displayDB();
                                        }
                                    });
                                }
                                else {
                                    alert("Savings would have a negative number");
                                }
                            }
                        });
                    }
                    else {
                        alert("Input must be a positive number");
                    }
                }
                else {
                    alert("Input must be a number");
                }
            });

            $("#transCsub").click(function() {
                let transferC = $("#transferC").val();
                if ($.isNumeric(transferC)) {
                    transferC = parseInt(transferC);
                    if (transferC > 0) {
                        $.ajax({
                            type: "post",
                            dataType: "html",
                            success: function(response) {
                                let testChecking = $(response).find("#checking");
                                testChecking = testChecking.text().trim();
                                testChecking = parseInt(testChecking);
                                if (transferC <= testChecking) {
                                    $.ajax({
                                        type: "post",
                                        data: {"transferC": transferC},
                                        dataType: "html",
                                        success: function(response) {
                                            let checkingVal = $(response).find("#checking");
                                            $("#checking").text(checkingVal.text());
                                            let savingsVal = $(response).find("#savings");
                                            $("#savings").text(savingsVal.text());
                                            addEntry("transfer", "checking", "savings", transferC);
                                            displayDB();
                                        }
                                    });
                                }
                                else {
                                    alert("Checking would have a negative number");
                                }
                            }
                        });
                    }
                    else {
                        alert("Input must be a positive number");
                    }
                }
                else {
                    alert("Input must be a number");
                }
            });

            $("#transSsub").click(function() {
                let transferS = $("#transferS").val();
                if ($.isNumeric(transferS)) {
                    transferS = parseInt(transferS);
                    if (transferS > 0) {
                        $.ajax({
                            type: "post",
                            dataType: "html",
                            success: function(response) {
                                let testSavings = $(response).find("#savings");
                                testSavings = testSavings.text().trim();
                                testSavings = parseInt(testSavings);
                                if (transferS <= testSavings) {
                                    $.ajax({
                                        type: "post",
                                        data: {"transferS": transferS},
                                        dataType: "html",
                                        success: function(response) {
                                            let checkingVal = $(response).find("#checking");
                                            $("#checking").text(checkingVal.text());
                                            let savingsVal = $(response).find("#savings");
                                            $("#savings").text(savingsVal.text());
                                            addEntry("transfer", "savings", "checking", transferS);
                                            displayDB();
                                        }
                                    });
                                }
                                else {
                                    alert("Savings would have a negative number");
                                }
                            }
                        });
                    }
                    else {
                        alert("Input must be a positive number");
                    }
                }
                else {
                    alert("Input must be a number");
                }
            });
            
            $("#reset").click(function() {
                $.ajax({
                    type: "post",
                    data: "reset",
                    dataType: "html",
                    success: function(response) {
                        let checkingVal = $(response).find("#checking");
                        $("#checking").text(checkingVal.text());
                        let savingsVal = $(response).find("#savings");
                        $("#savings").text(savingsVal.text());
                        addEntry("reset", "N/A", "N/A", "reset");
                        displayDB();
                    }
                });
            });
        });
    </script>           
</head>
<body>
    <div class="container-fluid">
    <h1> Welcome to the First Bank of HTML &#8482; </h1>
    <p> 
        Where all our clients are served! 
        <br>
        <!-- extra work: easter egg -->
        <span id="change" onclick="realWord()"> This website is under construction, as is our bank. </span>
    </p>
    <div class="col-4">
    <h2> Services Offered </h2>
    <ol>
        <li> 
            Current account information
            <br>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th> checking </th>
                        <th> savings </th>
                    </tr>
                    <tr>
                        <td id="checking"> <?= getCheckingValue(); ?></td>
                        <td id="savings"> <?= getSavingsValue(); ?> </td>
                        
                    </tr>
                </tbody>
            </table>
        </li>
        <li>
            Deposit money into checking
            <span data-toggle="tooltip" title="Input a positive amount">
                <input type="text" id="depositC">
            </span>
            <input type="submit" value="Submit" id="depCsub">
        </li>
        <li> 
            Deposit money into savings 
            <input type="text" id="depositS">
            <input type="submit" value="Submit" id="depSsub">
        </li>
        <li>
            Withdraw money from checking
            <input type="text" id="withdrawC">
            <input type="submit" value="Submit" id="withCsub">
        </li>
        <li>
            Withdraw money from savings
            <input type="text" id="withdrawS">
            <input type="submit" value="Submit" id="withSsub">
        </li>
        <li>  
            Transfer money from checking into savings
            <input type="text" id="transferC">
            <input type="submit" value="Submit" id="transCsub">
        </li>
        <li>  
            Transfer money from savings into checking
            <input type="text" id="transferS">
            <input type="submit" value="Submit" id="transSsub">
        </li>
        <li>
            Reset account to initial values
            <input type="submit" value="Start Fresh" id="reset">
        </li>
    </ol>
    </div>
    <div class="col-4">
    <h2> Loan Calculator </h2>
    <div>
        Loan Amount
        <input type="text" id="loan">
        <br>
        Choose number of years
        <select name="years" id="years">
            <option value="5"> 5 </option>
            <option value="15"> 15 </option>
            <option value="30"> 30 </option>
        </select>
        <br>
        Interest rate
        <input type="text" id="rate">
        <br>
        <input type="submit" value="Calculate" id="calc">
        <br>
        Your monthly payment is: $<span id="monthly"> </span>
        <br>
        <input type="range" min="2.5" max="10" value="4" step="0.1" id="slideRate">
        <br>
        Value: <span id="slide"></span>%
    </div>
    </div>
    </div>
    <svg height="100", width="100">
        <rect x="25%" y="25%" width="50%" height="50%" style="fill:#FA8072"></rect>
        <ellipse cx="50%" cy="50%" rx="12.5%" ry="12.5%" style="fill:#33BBFF"></ellipse>
        <polygon points="250,100 50,20 300,100" style="fill:black"></polygon>
        <polygon points="100,300 50,200 20,60" style="fill:black"></polygon>
    </svg>
    <div>
        <table id="recordTable" class="table table-striped table-bordered table-compact table-hover table-sm">
            <thead>
                <tr>
                    <th> Type </th>
                    <th> Source </th> 
                    <th> Destination </th> 
                    <th> Amount </th> 
                </tr>
            </thead> 
        </table>
    </div>
    <script>
        let initialRate = $("#slideRate").val();
        $("#slide").text(initialRate);
        $('#slideRate').click(function() {
            let rate = $("#slideRate").val();
            $("#slide").text(rate);
            let amount = $("#loan").val();
            let years = $("#years").val();
            if (!($.isNumeric(amount))) {
                alert("Inputs must be numbers");
                return 0;
            }
            let newAmt = parseFloat(amount);
            let newYears = parseFloat(years);
            let newRate = parseFloat(rate);
            if (!(newAmt > 0)) {
                alert("Inputs must be positive");
                return 0;
            }
            let m = newRate/12;
            let c = Math.pow((1 + m/100), (12 * newYears));
            let f = (newAmt * (m/100) * (c/(c-1))).toFixed(2);
            $("#monthly").text(f);
        })
        
        $('#calc').click(function() {
            let amount = $("#loan").val();
            let years = $("#years").val();
            let rate = $("#rate").val();
            if (!($.isNumeric(amount) && $.isNumeric(rate))) {
                alert("i=Inputs must be numbers");
                return 0;
            }
            let newAmt = parseFloat(amount);
            let newYears = parseFloat(years);
            let newRate = parseFloat(rate);
            if (!(newAmt > 0 && newRate > 0)) {
                alert("Inputs must be positive");
                return 0;
            }
            let m = newRate/12;
            let c = Math.pow((1 + m/100), (12 * newYears));
            let f = (newAmt * (m/100) * (c/(c-1))).toFixed(2);
            $("#monthly").text(f);
        })
    </script>
</body>
</html>