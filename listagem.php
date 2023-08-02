<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title> Euro Soccer Data</title>
</head>
<body>
<?php
    $servername = "localhost";
    $username = "root";
    $password = "123";
    $dbname = "hideki";
    $conexao = new mysqli($servername, $username, $password, $dbname);
    if ($conexao->connect_error)
    {
        die("Connection failed: " . $conexao->connect_error);
    }
    echo '
    <div class="w3-container w3-center">
	<div class="w3-col w3-cell" style="width: 20%" >
    	    <h1 class="w3-center w3-teal w3-round-large w3-margin">Leagues</h1>
    	    <table class="w3-table-all w3-centered w3-text-black">
                <thead>
            	    <tr class="w3-teal">
	                <th>Id</th>
	                <th>Name</th>
		        <th>Notation</th>
	    	    </tr>
	        </thead>';
    	        $sql = "SELECT * FROM leagues limit 10";
    	        $resultado = $conexao->query($sql);
    	        if ($resultado != null)
    	        foreach($resultado as $linha)
    	        {
                    echo '<tr>';
                    echo '<td>' .$linha['leagueID']. '</td>';
	            echo '<td>' .$linha['name']. '</td>';
		    echo '<td>' .$linha['understatNotation']. '</td>';
                    echo '</tr>';
    	        }
    	        echo '
            </table>
   	    <h1 class="w3-center w3-teal w3-round-large w3-margin">2016 Top Scorer</h1>
    	    <table class="w3-table-all w3-centered w3-text-black">
                <thead>
                    <tr class="w3-teal">
	                <th>Player Name</th>
	                <th>Gols</th>
	            </tr>
	        </thead>';
    	        $sql = "SELECT Name, count(shotResult) as Goals FROM hideki.shots inner join hideki.games on hideki.shots.gameID = hideki.games.gameID inner join hideki.players on hideki.shots.shooterID = hideki.players.playerID where shotResult='Goal' and season='2016' group by name order by count(shotResult) desc limit 1;";
    	        $resultado = $conexao->query($sql);
    	        if ($resultado != null)
    	            foreach($resultado as $linha)
    	            {
                        echo '<tr>';
                	echo '<td>' .$linha['Name']. '</td>';
	        	echo '<td>' .$linha['Goals']. '</td>';
                	echo '</tr>';
    	    	    }
    	        echo '
            </table>
	</div>
	<div class="w3-col w3-cell" style="width: 20%">
   	    <h1 class="w3-center w3-teal w3-round-large w3-margin">Teams</h1>
    	    <table class="w3-table-all w3-centered w3-text-black">
                <thead>
                    <tr class="w3-teal">
	                <th>Id</th>
	                <th>Name</th>
	            </tr>
	        </thead>';
    	        $sql = "SELECT * FROM teams limit 10";
    	        $resultado = $conexao->query($sql);
    	        if ($resultado != null)
    	            foreach($resultado as $linha)
    	            {
                        echo '<tr>';
                	echo '<td>' .$linha['teamID']. '</td>';
	        	echo '<td>' .$linha['name']. '</td>';
                	echo '</tr>';
    	    	    }
    	        echo '
            </table>
	</div>
	<div class="w3-col w3-cell" style="width: 20%">
   	    <h1 class="w3-center w3-teal w3-round-large w3-margin">Players</h1>
    	    <table class="w3-table-all w3-centered w3-text-black">
                <thead>
                    <tr class="w3-teal">
	                <th>Id</th>
	                <th>Name</th>
	            </tr>
	        </thead>';
    	        $sql = "SELECT * FROM players limit 10";
    	        $resultado = $conexao->query($sql);
    	        if ($resultado != null)
    	            foreach($resultado as $linha)
    	            {
                        echo '<tr>';
                	echo '<td>' .$linha['playerID']. '</td>';
	        	echo '<td>' .$linha['name']. '</td>';
                	echo '</tr>';
    	    	    }
    	        echo '
            </table>
	</div>
	<div class="w3-col w3-cell" style="width: 20%">
   	    <h1 class="w3-center w3-teal w3-round-large w3-margin">3 Top Scorer Teams</h1>
    	    <table class="w3-table-all w3-centered w3-text-black">
                <thead>
                    <tr class="w3-teal">
	                <th>Team</th>
	                <th>Goals</th>
	            </tr>
	        </thead>';
    	        $sql = "SELECT distinct name, sum(goals) as Goals FROM hideki.teamstats inner join hideki.teams on hideki.teamstats.teamID = hideki.teams.teamID group by name order by sum(goals) desc limit 3;";
    	        $resultado = $conexao->query($sql);
    	        if ($resultado != null)
    	            foreach($resultado as $linha)
    	            {
                        echo '<tr>';
                	echo '<td>' .$linha['name']. '</td>';
	        	echo '<td>' .$linha['Goals']. '</td>';
                	echo '</tr>';
    	    	    }
    	        echo '
            </table>
	</div>
	<div class="w3-col w3-cell" style="width: 20%">
   	    <h1 class="w3-center w3-teal w3-round-large w3-margin">The most balanced games in the season with more goals</h1>
    	    <table class="w3-table-all w3-centered w3-text-black">
                <thead>
                    <tr class="w3-teal">
	                <th>HomeTeam</th>
	                <th>AwayTeam</th>
			<th>drawProbability</th>
			<th>season</th>
	            </tr>
	        </thead>';
    	        $sql = "SELECT T1.name AS homeTeam, T2.name AS awayTeam, drawProbability, season FROM hideki.games JOIN teams AS T1 ON hideki.games.homeTeamID = T1.teamID JOIN teams AS T2 ON hideki.games.awayTeamID = T2.teamID WHERE season = (SELECT season FROM (SELECT season, SUM(homeGoals) + SUM(awayGoals) AS TotalGoals FROM hideki.games GROUP BY season ) AS goals_per_season WHERE TotalGoals = (SELECT MAX(TotalGoals)FROM (SELECT season, SUM(homeGoals) + SUM(awayGoals) AS TotalGoals FROM hideki.games GROUP BY season) AS max_goals_per_season)) ORDER BY drawProbability DESC LIMIT 5;";
    	        $resultado = $conexao->query($sql);
    	        if ($resultado != null)
    	            foreach($resultado as $linha)
    	            {
                        echo '<tr>';
                	echo '<td>' .$linha['homeTeam']. '</td>';
	        	echo '<td>' .$linha['awayTeam']. '</td>';
			echo '<td>' .$linha['drawProbability']. '</td>';
			echo '<td>' .$linha['season']. '</td>';
                	echo '</tr>';
    	    	    }
    	        echo '
            </table>
	</div>

    </div>
    <div class="w3-center w3-responsive">
	<h1 class="w3-center w3-teal w3-round-large w3-margin w3-responsive">Shots</h1>
    	<table class="w3-table-all w3-centered w3-text-black w3-responsive w3-margin">
            <thead>
                <tr class="w3-teal">
	            <th>gameId</th>
	            <th>shooterID</th>
		    <th>assisterID</th>
		    <th>minute</th>
		    <th>situation</th>
		    <th>lastAction</th>
		    <th>shotType</th>
		    <th>shotResult</th>
		    <th>xGoal</th>
		    <th>positionX</th>
		    <th>positionY</th>
	        </tr>
	    </thead>';
    	    $sql = "SELECT * FROM shots limit 10";
    	    $resultado = $conexao->query($sql);
    	    if ($resultado != null)
    	        foreach($resultado as $linha)
    	        {
                    echo '<tr>';
               	    echo '<td>' .$linha['gameID']. '</td>';
	            echo '<td>' .$linha['shooterID']. '</td>';
 		    echo '<td>' .$linha['assisterID']. '</td>';
 		    echo '<td>' .$linha['minute']. '</td>';
 		    echo '<td>' .$linha['situation']. '</td>';
 		    echo '<td>' .$linha['lastAction']. '</td>';
 		    echo '<td>' .$linha['shotType']. '</td>';
 		    echo '<td>' .$linha['shotResult']. '</td>';
 		    echo '<td>' .$linha['xGoal']. '</td>';
 		    echo '<td>' .$linha['positionX']. '</td>';
 		    echo '<td>' .$linha['positionY']. '</td>';
                    echo '</tr>';
    	    	}
    	    echo '
        </table>
	<h1 class="w3-center w3-teal w3-round-large w3-margin w3-responsive">TeamStats</h1>
    	<table class="w3-table-all w3-centered w3-text-black w3-responsive w3-margin">
            <thead>
                <tr class="w3-teal">
	            <th>gameId</th>
	            <th>teamID</th>
		    <th>season</th>
		    <th>date</th>
		    <th>location</th>
		    <th>goals</th>
		    <th>xgoals</th>
		    <th>shots</th>
		    <th>shotsOnTarget</th>
		    <th>deep</th>
		    <th>ppda</th>
 		    <th>fouls</th>
 		    <th>corners</th>
 		    <th>yellowCards</th>
 		    <th>redCards</th>
 		    <th>result</th>
	        </tr>
	    </thead>';
    	    $sql = "SELECT * FROM teamstats limit 10";
    	    $resultado = $conexao->query($sql);
    	    if ($resultado != null)
    	        foreach($resultado as $linha)
    	        {
                    echo '<tr>';
               	    echo '<td>' .$linha['gameID']. '</td>';
	            echo '<td>' .$linha['teamID']. '</td>';
 		    echo '<td>' .$linha['season']. '</td>';
 		    echo '<td>' .$linha['date']. '</td>';
 		    echo '<td>' .$linha['location']. '</td>';
 		    echo '<td>' .$linha['goals']. '</td>';
 		    echo '<td>' .$linha['xgoals']. '</td>';
 		    echo '<td>' .$linha['shots']. '</td>';
 		    echo '<td>' .$linha['shotsOnTarget']. '</td>';
 		    echo '<td>' .$linha['deep']. '</td>';
 		    echo '<td>' .$linha['ppda']. '</td>';
 		    echo '<td>' .$linha['fouls']. '</td>';
 		    echo '<td>' .$linha['corners']. '</td>';
 		    echo '<td>' .$linha['yellowCards']. '</td>';
		    echo '<td>' .$linha['redCards']. '</td>';
 		    echo '<td>' .$linha['result']. '</td>';
                    echo '</tr>';
    	    	}
    	    echo '
        </table>
    	<h1 class="w3-center w3-teal w3-round-large w3-margin w3-responsive">Appearances</h1>
    	<table class="w3-table-all w3-centered w3-text-black w3-responsive w3-margin">
            <thead>
                <tr class="w3-teal">
	            <th>gameId</th>
	            <th>playerID</th>
		    <th>goals</th>
		    <th>ownGoals</th>
		    <th>shots</th>
		    <th>xGoals</th>
		    <th>xGoalsBuildup</th>
		    <th>assists</th>
		    <th>keyPasses</th>
		    <th>xAssists</th>
		    <th>position</th>
		    <th>positionOrder</th>
		    <th>yellowCard</th>
		    <th>redCard</th>
		    <th>time</th>
		    <th>substituteIn</th>
		    <th>substituteOut</th>
		    <th>leagueID</th>
	        </tr>
	    </thead>';
    	    $sql = "SELECT * FROM appearances limit 10";
    	    $resultado = $conexao->query($sql);
    	    if ($resultado != null)
    	        foreach($resultado as $linha)
    	        {
                    echo '<tr>';
               	    echo '<td>' .$linha['gameID']. '</td>';
	            echo '<td>' .$linha['playerID']. '</td>';
 		    echo '<td>' .$linha['goals']. '</td>';
 		    echo '<td>' .$linha['ownGoals']. '</td>';
 		    echo '<td>' .$linha['shots']. '</td>';
 		    echo '<td>' .$linha['xGoals']. '</td>';
 		    echo '<td>' .$linha['xGoalsBuildup']. '</td>';
 		    echo '<td>' .$linha['assists']. '</td>';
 		    echo '<td>' .$linha['keyPasses']. '</td>';
 		    echo '<td>' .$linha['xAssists']. '</td>';
 		    echo '<td>' .$linha['position']. '</td>';
 		    echo '<td>' .$linha['positionOrder']. '</td>';
 		    echo '<td>' .$linha['yellowCard']. '</td>';
 		    echo '<td>' .$linha['redCard']. '</td>';
 		    echo '<td>' .$linha['time']. '</td>';
 		    echo '<td>' .$linha['substituteIn']. '</td>';
 		    echo '<td>' .$linha['substituteOut']. '</td>';
 		    echo '<td>' .$linha['leagueID']. '</td>';
                    echo '</tr>';
    	    	}
    	    echo '
        </table>
	<h1 class="w3-center w3-teal w3-round-large w3-margin w3-responsive">Games</h1>
    	<table class="w3-table-all w3-centered w3-text-black w3-responsive w3-margin">
            <thead>
                <tr class="w3-teal">
	            <th>gameId</th>
	            <th>leagueID</th>
		    <th>season</th>
		    <th>date</th>
		    <th>homeTeamID</th>
		    <th>awayTeamID</th>
		    <th>homeGoals</th>
		    <th>awayGoals</th>
		    <th>homeProbability</th>
		    <th>drawProbability</th>
		    <th>awayProbability</th>
		    <th>homeGoalsHalfTime</th>
		    <th>awayGoalsHalfTime</th>
		    <th>B365H</th>
		    <th>B365D</th>
		    <th>B365A</th>
		    <th>BWH</th>
		    <th>BWD</th>
		    <th>BWA</th>
 		    <th>IWH</th>
 		    <th>IWD</th>
		    <th>IWA</th>
		    <th>PSH</th>
		    <th>PSD</th>
		    <th>PSA</th>
		    <th>WHH</th>
		    <th>WHD</th>
		    <th>WHA</th>
		    <th>VCH</th> 
		    <th>VCD</th>
		    <th>VCA</th>
		    <th>PSCH</th>
		    <th>PSCD</th>
		    <th>PSCA</th>
	        </tr>
	    </thead>';
    	    $sql = "SELECT * FROM games limit 10";
    	    $resultado = $conexao->query($sql);
    	    if ($resultado != null)
    	        foreach($resultado as $linha)
    	        {
                    echo '<tr>';
               	    echo '<td>' .$linha['gameID']. '</td>';
	            echo '<td>' .$linha['leagueID']. '</td>';
 		    echo '<td>' .$linha['season']. '</td>';
 		    echo '<td>' .$linha['date']. '</td>';
 		    echo '<td>' .$linha['homeTeamID']. '</td>';
 		    echo '<td>' .$linha['awayTeamID']. '</td>';
		    echo '<td>' .$linha['homeGoals']. '</td>';
		    echo '<td>' .$linha['awayGoals']. '</td>';
 		    echo '<td>' .$linha['homeProbability']. '</td>';
 		    echo '<td>' .$linha['drawProbability']. '</td>';
 		    echo '<td>' .$linha['awayProbability']. '</td>';
 		    echo '<td>' .$linha['homeGoalsHalfTime']. '</td>';
 		    echo '<td>' .$linha['awayGoalsHalfTime']. '</td>';
 		    echo '<td>' .$linha['B365H']. '</td>';
 		    echo '<td>' .$linha['B365D']. '</td>';
 		    echo '<td>' .$linha['B365A']. '</td>';
 		    echo '<td>' .$linha['BWH']. '</td>';
 		    echo '<td>' .$linha['BWD']. '</td>';
 		    echo '<td>' .$linha['BWA']. '</td>';
 		    echo '<td>' .$linha['IWH']. '</td>';
 		    echo '<td>' .$linha['IWD']. '</td>';
 		    echo '<td>' .$linha['IWA']. '</td>';
 		    echo '<td>' .$linha['PSH']. '</td>';
 		    echo '<td>' .$linha['PSD']. '</td>';
 		    echo '<td>' .$linha['PSA']. '</td>';
 		    echo '<td>' .$linha['WHH']. '</td>';
 		    echo '<td>' .$linha['WHD']. '</td>';
 		    echo '<td>' .$linha['WHA']. '</td>';
 		    echo '<td>' .$linha['VCH']. '</td>';
 		    echo '<td>' .$linha['VCD']. '</td>';
 		    echo '<td>' .$linha['VCA']. '</td>';
 		    echo '<td>' .$linha['PSCH']. '</td>';
 		    echo '<td>' .$linha['PSCD']. '</td>';
 		    echo '<td>' .$linha['PSCA']. '</td>';
                    echo '</tr>';
    	    	}
    	    echo '
        </table
    </div>';
    $conexao->close();
?>
</body>
</html>
