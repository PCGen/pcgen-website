<?php

require_once('include/header.php.inc');

function writePcgenFileItems($url, $viewProd, $maxRecs)
{
    if ($url)
    {
        $rss = fetch_rss($url);
        $outCount = 0;
        $prevVerNum = array(0,0,0);

        foreach ($rss->items as $item)
        {
            $title  = $item['title'];
            $title  = strtolower($title);
            $title  = str_replace("_", " ", $title);
            $title  = str_replace("<br>", "<br /> ", $title);
            $title  = ucwords($title);

            $findme = 'Released';
            $found  = strpos($title, $findme);
            $title  = substr($title, 0, $found);

            $found  = strpos($title, " ");
            $ver    = substr($title, $found);
            $verNum = split("\.", $ver);
            $isProd = false;
            $isSnapshot = false;
            if (count($verNum > 1))
            {
              if ($verNum[1] % 2 == 0)
              {
                if (strpos(strtolower($title), "rc") == 0)
                {
                  $isProd = true;
                }
              }
            }
            if (strpos(strtolower($title), "snapshot") != 0)
            {
            	$isSnapshot = true;
            }

            if ($viewProd && $isProd && ($verNum[0] == $prevVerNum[0])
            	&& ($verNum[1] == $prevVerNum[1]))
            {
            	// When showing multiple prod versions, get the latest patch from each
            	$isProd = false;
            }

			if (($outCount < $maxRecs) && !$isSnapshot && (($viewProd && $isProd) || (!$viewProd && !$isProd)))
			{
              $pub    = date("Y-m-d", strtotime($item['pubdate']));

              $desc   = substr($item['description'], 2);
              $findme = '<br>';
              $found  = (4 + strpos($desc, $findme));
              $desc   = substr($desc, $found);
              $desc   = str_replace("<br>", "<br /> ", $desc);
              $desc   = str_replace("),", "),<br /> ", $desc);
              $desc   = str_replace("files:", "files:<br /> ", $desc);
              $desc   = str_replace("&release_id", "&amp;release_id", $desc);

              $href   = $item['link'];
              echo "<h3>$title</h3>\n";

              echo "<div class=\"published\">Released: $pub</div>\n";

              echo "<p>$desc</p>\n";
              echo "\n";
              $outCount++;

              if ($isProd)
              {
              	$prevVerNum = $verNum;
              }
            }
        }
    }
}

?>


    <div id="content" class="content">

        <h1>How to Get PCGen</h1>
        <p>PCGen is open-source software available for free under the <a href="http://www.gnu.org/copyleft/lgpl.html">LGPL License</a>. There are a couple of ways you can get it:
        <ul>
            <li><a href="#stable">Stable Download</a></li>
            <li><a href="#data">Stable Data Sets</a> - New data sets can be installed into an existing version of PCGen</li>
            <li><a href="#alpha">Alpha/Beta/RC Releases</a></li>
            <li><a href="#autobuild">Nightly Builds</a></li>
            <li><a href="#subversion">Subversion Access</a></li>
            <li><a href="http://wiki.pcgen.org/index.php?title=Roadmap">Roadmap</a> - Find out when you get your next fix!</li>
            <li><a href="http://sourceforge.net/projects/pcgen/files/PrettyLst/v%201.39%20build%208180/prettylst_1-39_build-8180.zip/download" target="_blank">PrettyLst</a> - PERL Utility for data coders.</li>
        </ul>

        <h2>Option 1. Download Latest Stable Release of PCGen<a class="" title="stable" name="stable"></a></h2>
        <p>Click on the link suited to your computer below. You can also look at
        the <a href="http://sourceforge.net/project/showfiles.php?group_id=25576&package_id=129606" style="font-size: 80%;">Full Package</a>
        for further files such as PDF documentation and alpha dataasets.
        </p>
        <p>This is the most recent stable or production PCGen Release. If you are
        using PCGen data sets from Code Monkey Publishing, you should be using a
        production release of PCGen.
        <a href="http://sourceforge.net/project/showfiles.php?group_id=25576&package_id=129606" style="font-size: 80%;">[View Older Production Releases]</a>
        </p>

        <div class="downloadbar"><a href="http://downloads.sourceforge.net/pcgen/pcgen5162_win_install.exe">Download PCGen 5.16.2 for Windows<small>&nbsp;</small></a></div><br />
        <div class="downloadbar"><a href="http://downloads.sourceforge.net/pcgen/pcgen5162_mac_build.dmg">Download PCGen 5.16.2 for Mac<small>&nbsp;</small></a></div><br />
        <div class="downloadbar"><a href="http://downloads.sourceforge.net/pcgen/pcgen5162_full.zip">Download PCGen 5.16.2 for Other Systems<small>&nbsp;</small></a></div><br/>

        <h3>Download Stable Data Sets<a class="" title="data" name="data"></a></h3>
        <p>These are stable data sets that are developed in between stable releases of PCGen but can be installed and used with a stable version of PCGen.
        </p>
        <div class="downloadbar"><a href="https://sourceforge.net/projects/pcgen/files/PCGen%20Stable%20Datasets/5.16.1%20OOC%20Data%20Sets/5161_pathfinder_role_playing_game_release4.zip/download">Download Pathfinder RPG dataset for PCGen 5.16.1<small>&nbsp;</small></a></div><br />
        <p>
        <a href="http://sourceforge.net/projects/pcgen/files/PCGen%20Stable%20Datasets/" style="font-size: 80%;">[View Stable Data Sets]</a>
        </p>

        <h2>Option 2. Download Alpha Releases<a class="" title="alpha" name="alpha"></a></h2>
        <p>These are development milestone releases designed to display the work in progress on PCGen
        and for use in testing new features. Use at own risk. Basic functionality is tested before
        each alpha and beta build though.
        </p><!--  <p>The beta releases have all of the features of the next production release of PCGen,
        and work focuses on fixing bugs and getting the program ready for a production release.<br /> -->
        <a href="http://sourceforge.net/project/showfiles.php?group_id=25576&package_id=21689" style="font-size: 80%;">[View Older Alpha and Beta Releases]</a>
        </p>
<?php

writePcgenFileItems("http://sourceforge.net/export/rss2_projfiles.php?group_id=25576&rss_limit=20", false, 5);

?>

        <h2>Option 3. Nightly Builds<a class="" title="autobuild" name="autobuild"></a></h2>
        <p>Autobuilds are compilations of the PCGen program and data taken
        direct from our source at regular intervals. They are not manually tested
        at all prior to upload, but are instead an excellent tool to allow people
        to test what we are currently working on.
        </p><p>See the
        <a href="07_autobuilds.php" style="font-size: 80%;">Autobuilds page</a>
        for these downloads.
        </p>

        <h2>Option 4. Subversion Access<a class="" title="subversion" name="subversion"></a></h2>
        <p>We use the Subversion service hosted by SourceForge for
        all our code and data. If you want direct access to the source, see the
        <a href="http://sourceforge.net/scm/?type=svn&group_id=25576" style="font-size: 80%;">Subversion usage page</a>
        </p>

    </div> <!-- div content -->

<?php

require_once('include/footer.php.inc');

?>
