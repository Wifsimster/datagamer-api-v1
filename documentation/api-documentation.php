<?php $api = new ApiDatagamer();?>

<html>
<head></head>
<body>
<div id="contentWrapper">		
	<div id="content_container">
		<div class="container">
		<h1>API Documentation</h1>
			<div class="row">
				<div class="span12 box">					
					<h2>Why ?</h2>
					<p>Many video games web site maintain their own database.
					The problem is that no web site gives a free access to their database !<br>
					Developers who want to set up video games web site need automatically to build a database.    
					</p>
					<h3>So why not build together your own database for everyone ?</h3>			
					<p>
					Is the main goal of DataGamer ! You can participate to build a public video games database. 
					</p>
					
					<h2>How ?</h2>
					<p>You have two ways to add games on the database.<br>
					<ul>
						<li>First you can directly add a game on the web site : <a href="add-game.php">Add a game</a>;</li>
						<li>Second way, you can use the API on your own web site.</li>
					</ul>
					</p>
					<p class="alert alert-info">In all cases, you need to generate your private <a href="generate-api-key.php">API key</a>. 
					This is the easiest way to verify your identity.</p>
					
					<h2>Formats</h2>
					<p>DataGamer API is build on REST architecture and provides three output formats (JSON, XML & HTML) to choose at your convenience.<br>
					By default JSON format is selected, but you can change the format in the API.</p>
					<pre class="prettyprint linenums">define('API_FORMAT', 'JSON');</pre>			
					<p class="alert alert-warning">For now, only the JSON format is implemented in the API.</p>
								
					<h2>Download</h2>
					<p>You can download the last release of the API just here :</p>
			
					<div class="well" style="max-width: 400px; margin: 0 auto 10px;">
             			<a href="api/" class="btn btn-large btn-block btn-primary">API Datagamer v1.4</a>
            		</div>
					
					<h2>Methods<small>(In documentation, return data will be displayed in JSON)</small></h2>
					<h3>Initialize</h3>
					<p>You need to include the API in your web site to play with it.</p>
					<pre class="prettyprint linenums">require_once 'api-datagmer.php';</pre>
					<p>After what, you just have to declare the API in your page.</p>
					<pre class="prettyprint linenums">$api = new ApiDatagamer();</pre>
					<p>Don't forget to specify your API key in the API !</p>
					<pre class="prettyprint linenums">define('API_KEY', 'yourAPIKey');</pre>
					
					<h3>Errors</h3>
					<p>All API's methods can throws errors, so you need to try/catch each method you used.</p>
					<pre class="prettyprint linenums">try<br>{<br>	// Your method<br>}<br>catch (Exception $e)<br>{<br>	echo $e->getMessage();<br>}</pre>
					
					<h3>List games</h3>
					<p>Find all games in database.</p>
					
					<h4>Synopsis</h4>
					<pre class="prettyprint linenums">findAllGames($order, $by);</pre>
					<h4>Optional</h4>
					<dl class="dl-horizontal">
						<dt>$order</dt>
						<dd>ASC</dd><dd>DESC</dd>
						<dt>$by</dt>
						<dd>id</dd><dd>name</dd><dd>cover</dd><dd>releaseDate</dd>
					</dl>
					<h4>Return</h4>
					<p>This method will return an array of game, like this :</p>
					<pre class="prettyprint linenums">
					<?php
					try
					{
						$data = $api->findRandomGames(2);
						print_r($data);
					}
					catch (Exception $e)
					{
						echo $e->getMessage();
					}
					?>
					</pre>
					
					<h3><span class="label label-important">New</span> Rate & appreciate a game</h3>
					<p>We just add two new
						attributes <strong>grade</strong> and <strong>appreciation</strong> for the game object.<br>
						
						<dl class="dl-horizontal">
							<dt>grade</dt>
							<dd>This is the score of the game, evaluate by all players.<br>
							Value ranges between 0 and 10.<br>
							You can change this value using the method <strong>addGrade()</strong>.</dd>
							<dt>appreciation</dt>
							<dd>This attribute enables us to know if some data in a game is wrong or inaccurate.<br>
							Can take only the value -1 or +1.<br>
							You can change this value using the method <strong>addAppreciation()</strong>.</dd>
						</dl>
					</p>
					
					<h4>Synopsis</h4>		
					<pre class="prettyprint linenums">$apiDatagamer->addGrade($idGame, $value);<br>$apiDatagamer->addAppreciation($idGame, $value);</pre>
					
					<h4>Return</h4>
					<p>Theses methods will return the update game array.</p>
					
					<h3><span class="label label-important">New</span> Add a game</h3>
					<p>You can now add a game from your own website !</p>
					<pre class="prettyprint linenums">$apiDatagamer->addGame($name, $cover, $releaseDate, $editor, $developer, $idGenres, $idPlatforms);</pre>
					<h4>Mandatory</h4>
					<dl class="dl-horizontal">
						<dt>$name</dt>
						<dd>String</dd>
						<dt>$cover</dt>
						<dd>Must be an URL [http://url/name.extension]</dd>
						<dt>$releaseDate</dt>
						<dd>Timestamp</dd>
						<dt>$editor</dt>
						<dd>String</dd>
						<dt>$developer</dt>
						<dd>String</dd>
						<dt>$idGenres</dt>
						<dd>Array of id</dd>
						<dt>$idPlatforms</dt>
						<dd>Array of id</dd>
					</dl>
					
					<h4>Example</h4>		
					<pre class="prettyprint linenums">$idGenres = array(0 => "1", 1 => "2");<br>$idPlatforms = array(0 => "1", 1 => "2");<br>$data = $api->addGame("testName", "COVER_URL", "1256953732", "testEditor", "testDeveloper", $idGenres, $idPlatforms);</pre>
					
					<p>You can obtain the id to genre and platform with the respectives methods findAllGenres($order, $by) and findAllPlatforms($order, $by).</p>
								
					<h4>Return</h4>
					<p>This method will return the insert game array.</p>					
		
					<h3>List editors, developers, platforms and genres</h3>
					<p>Find respectively all editors, developers, platforms and genres in database.</p>
					
					<h4>Synopsis</h4>
					<pre class="prettyprint linenums">findAllEditors($order, $by);<br>findAllDevelopers($order, $by);<br>findAllPlatforms($order, $by);<br>findAllGenres($order, $by);</pre>
					
					<h4>Optional</h4>
					<dl class="dl-horizontal">
						<dt>$order</dt>
						<dd>ASC</dd><dd>DESC</dd>
						<dt>$by</dt>
						<dd>id</dd><dd>name</dd>
					</dl>
					<h4>Return</h4>
					<p>This method will return an array of editors, developers, platforms and genres, like this :</p>
					<pre class="prettyprint linenums">
					<?php
					try
					{
						$data = $api->findAllEditors();
						$data = array_slice($data, 0, 2);
						print_r($data);
					}
					catch (Exception $e)
					{
						echo $e->getMessage();
					}
					?>
					</pre>
					
					<h3>Find an objet by id</h3>
					<p>All objects in database can be found by their id.</p>
					
					<h4>Synopsis</h4>
					<pre class="prettyprint linenums">findGameById($id);<br>findEditorById($id);<br>findDeveloperbyId($id);<br>findPlatformById($id);<br>findGenreById($id);</pre>
					
					<h4>Mandatory</h4>
					<dl class="dl-horizontal">
						<dt>$id</dt>
						<dd>Integer</dd>
					</dl>
					<h4>Return</h4>
					<p>Theses methods will return the object, for sample a game :</p>
					<pre class="prettyprint linenums">
					<?php
					try
					{
						$data = $api->findGameById(1);
						print_r($data);
					}
					catch (Exception $e)
					{
						echo $e->getMessage();
					}
					?>
					</pre>
					
					<h3>Find games by criteria</h3>
					<p>You can obviously search games by criteria.</p>
					
					<h4>Synopsis</h4>
					<pre class="prettyprint linenums">findGameByCriteria($criteria, $order, $by, $startLimit, $limit);</pre>
					
					<h4>Mandatory</h4>
					<dl class="dl-horizontal">
						<dt>$criteria</dt>
						<dd>Array[id]</dd><dd>Array[name]</dd><dd>Array[cover]</dd><dd>Array[releaseDate]</dd>
					</dl>
					<h4>Optional</h4>
					<dl class="dl-horizontal">
						<dt>$order</dt>
						<dd>ASC</dd><dd>DESC</dd>
						<dt>$by</dt>
						<dd>id</dd><dd>name</dd><dd>cover</dd><dd>releaseDate</dd>
						<dt>$startLimit</dt>
						<dd>Integer</dd>
						<dt>limit</dt>
						<dd>Integer</dd>
					</dl>
					
					<h4>Example</h4>
					<pre class="prettyprint linenums">$criteria["name"] = "Max Payne 3";<br>findGameByCriteria($criteria);</pre>
					
					<h4>Return</h4>
					<p>This method will return an array of games.</p>
					<pre class="prettyprint linenums">
					<?php
					try
					{
						$criteria["name"] = "Max Payne 3";
						
						$data = $api->findGameByCriteria($criteria);
						print_r($data);
					}
					catch (Exception $e)
					{
						echo $e->getMessage();
					}
					?>
					</pre>
					
					<h3>Find random games</h3>
					<p>You can also find games randomly, you just need to specify the number of games you want to return.</p>
					
					<h4>Synopsis</h4>
					<pre class="prettyprint linenums">findRandomGames($number);</pre>
					
					<h4>Mandatory</h4>
					<dl class="dl-horizontal">
						<dt>$number</dt>
						<dd>Integer</dd>
					</dl>
					<h4>Example</h4>
					<pre class="prettyprint linenums">findRandomGames(1);</pre>
					<h4>Return</h4>
					<p>This method will return an array of games.</p>
					<pre class="prettyprint linenums">
					<?php
					try
					{
						$data = $api->findRandomGames(1);
						print_r($data);
					}
					catch (Exception $e)
					{
						echo $e->getMessage();
					}
					?>
					</pre>					
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>