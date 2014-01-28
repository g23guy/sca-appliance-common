<?PHP //echo "<!-- Modified: Date       = 2014 Jan 28 -->\n"; ?>
<?PHP
	if(isset($_SERVER['HTTP_USER_AGENT']))
	{
		include 'checklogin.php';
	}
?>

<!DOCTYPE html>
<HTML>
<script>
function showPattern(patternOutput,patternLocation)
{
alert(patternOutput + "\n\n" + "Pattern: " + patternLocation);
}
</script>
<?PHP
	ini_set('include_path', '/srv/www/htdocs/sca/');
	include 'db-config.php';
	$sver = '1.0.16';
	if ( isset($argc) ) {
		$givenArchiveID = $argv[1];
		if ( ! is_numeric($givenArchiveID) ) {
			die("<FONT SIZE=\"-1\"><B>ERROR</B>: Invalid ArchiveID, Only numeric values allowed.</FONT><BR>");			
		}
		//echo "<!-- Parameters: CmdLine  = argc='$argc', argv[1]='$argv[1]' -->\n";
	} else {
		if ( isset($_GET) ) {
			$givenArchiveID = $_GET['aid'];
			if ( ! is_numeric($givenArchiveID) ) {
				die("<FONT SIZE=\"-1\"><B>ERROR</B>: Invalid ArchiveID, Only numeric values allowed.</FONT><BR>");			
			}
			//echo "<!-- Parameters: GET      = _GET['aid']='$givenArchiveID' -->\n";
		} else {
			die("<FONT SIZE=\"-1\"><B>ERROR</B>: Missing ArchiveID and/or server name</FONT><BR>");
		}
	}
	//echo "<!-- Parameters: Values   = givenArchiveID='$givenArchiveID' -->\n";

// ** SUMMARY DATA ** //
	include 'db-open.php';
	$query="SELECT * FROM Archives WHERE ArchiveID=$givenArchiveID";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	//echo "<!-- Query: Submitted     = $query -->\n";
	if ( $result ) {
		//echo "<!-- Query: Result        = Success -->\n";
		//echo "<!-- Query: Rows          = $num -->\n";
	} else {
		//echo "<!-- Query: Results       = FAILURE -->\n";
	}
	include 'db-close.php';
	$row_cell = mysql_fetch_row($result);
	$ArchiveID = htmlspecialchars($row_cell[0]);
	$FileLocation = $row_cell[1];
	if( isset($FileLocation) ) { $FileLocation = htmlspecialchars($FileLocation); }
	$Filename = htmlspecialchars($row_cell[2]);
	$ArchiveState = htmlspecialchars($row_cell[3]);
	$ArchiveEvent = htmlspecialchars($row_cell[4]);
	$ArchiveMessage = htmlspecialchars($row_cell[5]);
	$RetryCount = htmlspecialchars($row_cell[6]);
	$AssignedAgentID = htmlspecialchars($row_cell[7]);
	$AssignedWorkerID = htmlspecialchars($row_cell[8]);
	$ReportDate = htmlspecialchars($row_cell[9]);
	$ReportTime = htmlspecialchars($row_cell[10]);
	$ArchiveDate = htmlspecialchars($row_cell[11]);
	$ArchiveTime = htmlspecialchars($row_cell[12]);
	$SRNum = htmlspecialchars($row_cell[13]);
	$ServerName = htmlspecialchars($row_cell[14]);
	$AnalysisTime = htmlspecialchars($row_cell[15]);
	$VersionKernel = htmlspecialchars($row_cell[16]);
	$VersionSupportconfig = htmlspecialchars($row_cell[17]);
	$Architecture = htmlspecialchars($row_cell[18]);
	$Hardware = htmlspecialchars($row_cell[19]);
	$Distro = htmlspecialchars($row_cell[20]);
	$DistroSP = htmlspecialchars($row_cell[21]);
	$OESDistro = $row_cell[22];
	if( isset($OESDistro) ) { $OESDistro = htmlspecialchars($OESDistro); }
	$OESDistroSP = htmlspecialchars($row_cell[23]);
	$Hypervisor = $row_cell[24];
	if( isset($Hypervisor) ) { $Hypervisor = htmlspecialchars($Hypervisor); }
	$HypervisorIdentity = $row_cell[25];
	if( isset($HypervisorIdentity) ) { $HypervisorIdentity = htmlspecialchars($HypervisorIdentity); }
	$PatternsTested = htmlspecialchars($row_cell[26]);
	$PatternsApplicable = htmlspecialchars($row_cell[27]);
	$PatternsCritical = htmlspecialchars($row_cell[28]);
	$PatternsWarning = htmlspecialchars($row_cell[29]);
	$PatternsRecommended = htmlspecialchars($row_cell[30]);
	$PatternsSuccess = htmlspecialchars($row_cell[31]);
	if ( $DistroSP == 0 ) { $DistroSP = 'None'; }
	if ( $OESDistroSP == 0 ) { $OESDistroSP = 'None'; }

	$ColorRed = "#FF0000";
	$ColorYellow = "#FFFF00";
	$ColorRoyalBlue = "#1975FF";
	$ColorGreen = "#00FF00";
	$ColorWhite = "#FFFFFF";
	$ColorGray = "#EEEEEE";
	$ColorDarkGray = "#898989";
	$ColorLightGray = "#CDCDCD";
	$ColorSteelBlue = "#B0C4DE";
	$ColorPeach = "#FFCC99";
	$ColorBlack = "#000000";
	$ColorBlue = "#0000FF";
	$ColorCritical = $ColorRed;
	$ColorWarning = $ColorYellow;
	$ColorRecommended = $ColorRoyalBlue;
	$ColorSuccess = $ColorGreen;
	$ColorSectionText = $ColorBlue;
	$ColorSection = $ColorPeach;

	$WidthSeverity = "2%";
	$WidthClass = "6%";
	$WidthCategory = "5%";
	$WidthComponent = "5%";
	$WidthSolutions = "8%";

// ** REPORT INFORMATION ** //
	echo "<HEAD>\n";
	echo "<TITLE>SCA Report for $ServerName</TITLE>\n";
	echo "<STYLE TYPE=\"text/css\">\n";
	echo "	a {text-decoration: none}	/* no underlined links */\n";
	echo "	a:link {color:$ColorBlue;}	/* unvisited link */\n";
	echo "	a:visited {color:$ColorBlue;}	/* visited link */\n";
	echo "</STYLE>\n";
?>
<SCRIPT>
function toggle(className)
{
 className = className.replace(/ /g,".");
 var elements = document.querySelectorAll("." + className); for(var i=0; i<elements.length; i++)
 {
  if( elements[i].style.display=='none' )
    {
      elements[i].style.display = '';
    }
    else
    {
      elements[i].style.display = 'none';
    }
 }
}
</SCRIPT>
<?PHP
	echo "</HEAD>\n";
	echo "<BODY BGPROPERTIES=FIXED BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\">\n";
	echo "\n<H1>Supportconfig Analysis Report</H1>\n";
	echo "<H2><HR />Server Information</H2>\n";
	echo "\n<TABLE WIDTH=100%>\n";
	echo "<TR><TD><B>Analysis Date:</B></TD><TD>$ReportDate $ReportTime</TD></TR>\n";
	echo "<TR><TD><B>Supportconfig Run Date:</B></TD><TD>$ArchiveDate $ArchiveTime</TD></TR>\n";
	if( strlen($FileLocation) > 0 ) {
		$pos = strpos($FileLocation, 'file://');
		if( $pos === false ) {
			echo "<TR><TD><B>Supportconfig File:</B></TD><TD><A HREF=\"$FileLocation/$Filename\">$Filename</A></TD></TR>\n";
		} else {
			echo "<TR><TD><B>Supportconfig File:</B></TD><TD>$Filename</TD></TR>\n";
		}
	} else {
		echo "<TR><TD><B>Supportconfig File:</B></TD><TD>$Filename</TD></TR>\n";
	}
	echo "</TABLE>\n";

	echo "\n<TABLE CELLPADDING=\"5\">\n";
	echo "<TR><TD>&nbsp;</TD></TR>\n";
	if ( $SRNum > 0 ) {
		if ( $SRView == 0 ) {
			echo "<TR><TD><B>SR#:</B></TD><TD><A HREF=\"https://secure-www.novell.com/center/eservice/\" TARGET=\"_blank\">$SRNum</A></TD></TR>\n";
		} elseif ($SRView == 1 ) {
			echo "<TR><TD><B>SR#:</B></TD><TD><A HREF=\"https://crystal10.innerweb.novell.com/CE10/viewer.jsp?id=4843&prompts=SRID=$SRNum\" TARGET=\"_blank\">$SRNum</A></TD></TR>\n";
		} else {
			echo "<TR><TD><B>SR#:</B></TD><TD>$SRNum</TD></TR>\n";
		}
	} else {
		echo "<TR><TD><B>SR#:</B></TD><TD>Missing</TD></TR>\n";
	}
	echo "<TR><TD><B>Server Name:</B></TD><TD>$ServerName</TD><TD><B>Hardware:</B></TD><TD>$Hardware</TD></TR>\n";
	echo "<TR><TD><B>Distribution:</B></TD><TD>$Distro ($Architecture)</TD><TD><B>Service Pack:</B></TD><TD>$DistroSP</TD></TR>\n";
	if ( isset($OESDistro) ) { 
		echo "<TR><TD><B>OES Distribution:</B></TD><TD>$OESDistro ($Architecture)</TD><TD><B>OES Service Pack:</B></TD><TD>$OESDistroSP</TD></TR>\n";
	}
	if ( isset($Hypervisor) ) {
		echo "<TR><TD><B>Hypervisor:</B></TD><TD>$Hypervisor</TD><TD><B>Identity:</B></TD><TD>$HypervisorIdentity</TD></TR>\n";
	}
	echo "<TR><TD><B>Kernel Version:</B></TD><TD>$VersionKernel</TD><TD><B>Supportconfig Version:</B></TD><TD>$VersionSupportconfig</TD></TR>\n";
	echo "</TABLE>\n";
	echo "<HR />\n";

// ** ANALYSIS OVERVIEW ** //
	echo "\n<H2>Analysis Overview</H2>\n";
	echo "\n<TABLE STYLE=\"border:3px solid black;border-collapse:collapse;\">\n";
	echo "<TR><TD>&nbsp;</TD><TD>Available Patterns:</TD><TD>$PatternsTested</TD><TD>&nbsp;</TD></TR>\n";
	echo "<TR><TD>&nbsp;</TD><TD>Applicable to Server:</TD><TD>$PatternsApplicable</TD><TD>&nbsp;</TD></TR>\n";
	echo "<TR BGCOLOR=\"$ColorWhite\" STYLE=\"border:1px solid black;\"><TD BGCOLOR=\"$ColorCritical\">&nbsp;&nbsp;&nbsp;</TD>";
	echo "<TD><a href=\"#Critical\">Critical:</a></TD><TD>$PatternsCritical</TD>";
	echo "<TD BGCOLOR=\"$ColorCritical\">&nbsp;&nbsp;&nbsp;</TD></TR>\n";
	echo "<TR BGCOLOR=\"$ColorWhite\" STYLE=\"border:1px solid black;\"><TD BGCOLOR=\"$ColorWarning\">&nbsp;&nbsp;&nbsp;</TD>";
	echo "<TD><a href=\"#Warning\">Warning:</a></TD><TD>$PatternsWarning</TD>";
	echo "<TD BGCOLOR=\"$ColorWarning\">&nbsp;&nbsp;&nbsp;</TD></TR>\n";
	echo "<TR BGCOLOR=\"$ColorWhite\" STYLE=\"border:1px solid black;\"><TD BGCOLOR=\"$ColorRecommended\">&nbsp;&nbsp;&nbsp;</TD>";
	echo "<TD><a href=\"#Recommended\">Recommended:</a></TD><TD>$PatternsRecommended</TD>";
	echo "<TD BGCOLOR=\"$ColorRecommended\">&nbsp;&nbsp;&nbsp;</TD></TR>\n";
	echo "<TR BGCOLOR=\"$ColorWhite\" STYLE=\"border:1px solid black;\"><TD BGCOLOR=\"$ColorSuccess\">&nbsp;&nbsp;&nbsp;</TD>";
	echo "<TD><a href=\"#Success\">Success:</a></TD><TD>$PatternsSuccess</TD>";
	echo "<TD BGCOLOR=\"$ColorSuccess\">&nbsp;&nbsp;&nbsp;</TD></TR>\n";
	echo "</TABLE>\n";
	echo "<HR />\n";

// ** ANALYSIS DETAIL ** //
	echo "\n<H2>Analysis Detail</H2>\n";
	$Severities = array(4, 3, 1, 0);
	$SeverityTag = array('Success', 'Recommended', 'NULL', 'Warning', 'Critical');
	$SeverityColor = array($ColorSuccess, $ColorRecommended, $ColorBlack, $ColorWarning, $ColorCritical);
	$SeverityPatterns = array($PatternsSuccess, $PatternsRecommended, 0, $PatternsWarning, $PatternsCritical);

// ** CRITICIAL, WARNING, RECOMMENDED, SUCCESS ** //
	foreach ($Severities as $severity) {
		echo "\n<H2>Conditions Evaluated as $SeverityTag[$severity]<A NAME=\"$SeverityTag[$severity]\"></A></H2>\n";
		if ( $SeverityPatterns[$severity] > 0 ) {
			include 'db-open.php';
			//echo "<!-- Patterns:            = $SeverityTag[$severity], $SeverityPatterns[$severity] -->\n";
			$query="SELECT Class, Category, Component, ResultStr, PatternID, PatternLocation, PrimaryLink, TID, BUG, URL01, URL02, URL03, URL04, URL05, URL06, URL07, URL08, URL09, URL10 FROM Results WHERE ResultsArchiveID=$givenArchiveID AND Result=$severity ORDER BY Class, Category, Component, ResultStr";
			$result=mysql_query($query);
			$num=mysql_numrows($result);
			//echo "<!-- Query: Submitted     = $query -->\n";
			if ( $result ) {
				//echo "<!-- Query: Result        = Success -->\n";
				//echo "<!-- Query: Rows          = $num -->\n";
			} else {
				//echo "<!-- Query: Results       = FAILURE -->\n";
			}
			include 'db-close.php';

			echo "\n<TABLE STYLE=\"border:3px solid black;border-collapse:collapse;\" WIDTH=\"100%\" CELLPADDING=\"2\">\n";
			echo "<TR COLOR=\"$ColorBlack\">";
			echo "<TH BGCOLOR=\"$SeverityColor[$severity]\"></TH>";
			echo "<TH BGCOLOR=\"$ColorGray\" COLSPAN=\"3\">Category</TH>";
			echo "<TH>Message</TH>";
			echo "<TH>Solutions</TH>";
			echo "<TH BGCOLOR=\"$SeverityColor[$severity]\"></TH>";
			echo "</TR>\n";

			unset($NewClass);
			$SubClassRows = array();
			for ( $j=0, $i=0; $j < $num; $j++, $i++ ) {
				$row_cell = mysql_fetch_row($result);
				$Class = $row_cell[0];
				$Category = $row_cell[1];
				$Component = $row_cell[2];
				$ResultStr = $row_cell[3];
				$PatternID = $row_cell[4];
				$PatternLocation = $row_cell[5];
				$PrimLink = $row_cell[6];
				$TID = $row_cell[7];
				$BUG = $row_cell[8];
				$URLS = array($row_cell[9], $row_cell[10], $row_cell[11], $row_cell[12], $row_cell[13], $row_cell[14], $row_cell[15], $row_cell[16], $row_cell[17], $row_cell[18]);
				//if ( "$Class" == "Basic Health" ) { $DisplaySet = 'active'; } else { $DisplaySet = 'none'; } # Selects a class to be expanded by default
				$DisplaySet = 'none';

				if ( isset($NewClass) ) {
					//echo "<!-- Variable: NewClass = $NewClass, Class = $Class -->\n";
					if ( "$NewClass" != "$Class" ) {
						//Create the first table row in the collapsible Class
						$SubClassCount = count($SubClassRows);
						printHeaderRow();
						printRows();
						unset($SubClassRows);
						$SubClassRows = array();
						$i = 0;
						$NewClass = $Class;
					}
					loadRow();
				} else {
					//echo "<!-- Variable: NewClass = Not Set, Assigning to $Class -->\n";
					$NewClass = $Class;
					loadRow();
				}
			}
			//Create the first table row in the collapsible Class
			$SubClassCount = count($SubClassRows);
			printHeaderRow();
			printRows();
			echo "</TABLE>\n";
		} else {
			echo "\n<!-- Patterns:            = $SeverityTag[$severity], $SeverityPatterns[$severity] -->\n";
			echo "\n<TABLE STYLE=\"border:3px solid black;border-collapse:collapse;\" WIDTH=\"100%\" CELLPADDING=\"2\">\n";
			echo "<TR BGCOLOR=\"$ColorWhite\" STYLE=\"border:1px solid black;\" >";
			echo "<TD BGCOLOR=\"$SeverityColor[$severity]\" WIDTH=\"$WidthSeverity\">&nbsp;</TD>";
			echo "<TD COLSPAN=\"5\" ALIGN=\"center\"><B>None</B></TD>";
			echo "<TD BGCOLOR=\"$SeverityColor[$severity]\" WIDTH=\"$WidthSeverity\">&nbsp;</TD>";
			echo "</TR>\n";
			echo "</TABLE>\n";
		}
	}

function loadRow()
{
	global $ColorCritical, $ColorWarning, $ColorRecommended, $ColorSuccess, $ColorWhite, $ColorGray, $ColorDarkGray, $ColorBeige, $ColorBlack, $ColorBlue, $ColorSectionText, $ColorSection;
	global $WidthSeverity, $WidthClass, $WidthCategory, $WidthComponent, $WidthSolutions;
	global $SubClassRows, $severity, $DisplaySet, $SeverityColor, $Class, $Category, $Component, $PrimLink, $ResultStr, $PatternLocation, $TID, $BUG, $URLS;
	global $i;

	$SubClassRows[$i] = "<TR STYLE=\"border:1px solid black; background: $ColorWhite; display:$DisplaySet;\" CLASS=\"$Class\">";
	$SubClassRows[$i] = "$SubClassRows[$i]<TD BGCOLOR=\"$SeverityColor[$severity]\" WIDTH=\"$WidthSeverity\">&nbsp;</TD>";
	$SubClassRows[$i] = "$SubClassRows[$i]<TD BGCOLOR=\"$ColorGray\" WIDTH=\"$WidthClass\">$Class</TD>";
	$SubClassRows[$i] = "$SubClassRows[$i]<TD BGCOLOR=\"$ColorGray\" WIDTH=\"$WidthCategory\">$Category</TD>";
	$SubClassRows[$i] = "$SubClassRows[$i]<TD BGCOLOR=\"$ColorGray\" WIDTH=\"$WidthComponent\">$Component</TD>";
	if ( isset($PrimLink) ) {
		$SubClassRows[$i] = "$SubClassRows[$i]<TD><A HREF=\"$PrimLink\" TARGET=\"_blank\">$ResultStr</A>&nbsp;&nbsp;<A ID=\"PatternLocation\" HREF=\"#\" onClick=\"showPattern('$ResultStr','$PatternLocation');return false;\">&nbsp;</A></TD>";
	} else {
		$SubClassRows[$i] = "$SubClassRows[$i]<TD>$ResultStr&nbsp;&nbsp;<A ID=\"PatternLocation\" HREF=\"#\" onClick=\"showPattern('$ResultStr','$PatternLocation');return false;\">&nbsp;</A></TD>";
	}
	$SubClassRows[$i] = "$SubClassRows[$i]<TD WIDTH=\"$WidthSolutions\">";
	if ( isset($TID) ) { $SubClassRows[$i] = "$SubClassRows[$i]<A HREF=\"$TID\" TARGET=\"_blank\">TID</A>&nbsp;&nbsp;"; }
	if ( isset($BUG) ) { $SubClassRows[$i] = "$SubClassRows[$i]<A HREF=\"$BUG\" TARGET=\"_blank\">BUG</A>&nbsp;&nbsp;"; }
	foreach ($URLS as $URL_PAIR) {
		if ( isset($URL_PAIR) ) {
			$URL_ELEMENTS = explode('=', $URL_PAIR);
			$TAG = $URL_ELEMENTS[0];
			unset($URL_ELEMENTS[0]);
			$URL = implode('=', $URL_ELEMENTS);
			$SubClassRows[$i] = "$SubClassRows[$i]<A HREF=\"$URL\" TARGET=\"_blank\">$TAG</A>&nbsp;&nbsp;";
		}
	}
	$SubClassRows[$i] = "$SubClassRows[$i]</TD><TD BGCOLOR=\"$SeverityColor[$severity]\" WIDTH=\"$WidthSeverity\">&nbsp;</TD></TR>";
	//echo "<!-- loadRow: SubClassRows[$i] = $SubClassRows[$i] -->\n";
}

function printHeaderRow()
{
	global $ColorCritical, $ColorWarning, $ColorRecommended, $ColorSuccess, $ColorWhite, $ColorGray, $ColorDarkGray, $ColorBeige, $ColorBlack, $ColorBlue, $ColorSectionText, $ColorSection;
	global $WidthSeverity, $WidthClass, $WidthCategory, $WidthComponent, $WidthSolutions;
	global $SubClassCount, $severity, $SeverityColor, $SeverityTag, $NewClass;

	//echo "<!-- printHeaderRow: SubClassCount = $SubClassCount -->\n";
	echo "<TR STYLE=\"border:1px solid black;color: $ColorSectionText; background: $ColorSection; font-size:80%; font-weight:normal\">";
	echo "<TD BGCOLOR=\"$SeverityColor[$severity]\" WIDTH=\"$WidthSeverity\">&nbsp;</TD>";
	echo "<TD BGCOLOR=\"$ColorSection\" WIDTH=\"$WidthClass\"><A ID=\"NewClass\" TITLE=\"Click to Expand/Collapse\" HREF=\"#\" onClick=\"toggle('$NewClass');return false;\">$NewClass</A></TD>";
	echo "<TD BGCOLOR=\"$ColorSection\" WIDTH=\"$WidthCategory\">&nbsp;</TD>";
	echo "<TD BGCOLOR=\"$ColorSection\" WIDTH=\"$WidthComponent\">&nbsp;</TD>";
	echo "<TD><A ID=\"NewClass\" TITLE=\"Click to Expand/Collapse\" HREF=\"#\" onClick=\"toggle('$NewClass');return false;\">$SubClassCount $SeverityTag[$severity] $NewClass Message(s)</A></TD>";
	echo "<TD WIDTH=\"$WidthSolutions\">&nbsp;</TD>";
	echo "<TD BGCOLOR=\"$SeverityColor[$severity]\" WIDTH=\"$WidthSeverity\">&nbsp;</TD>";
	echo "</TR>\n";
}

function printRows()
{
	global $SubClassCount, $SubClassRows;

	//echo "<!-- printRows: Printing $SubClassCount Rows -->\n";
	for ( $x=0; $x < $SubClassCount; $x++ ) {
		echo "$SubClassRows[$x]\n";
	}
}

	echo "\n\n<HR />\n";
	echo "\n<TABLE WIDTH=\"100%\">\n<TR>";
	echo "<TD ALIGN=\"left\">Client: reportfull.php v$sver [$AssignedAgentID:$AssignedWorkerID:$ArchiveID] (Report Generated by: <A HREF=\"http://susestudio.com/a/Vj5bpn/sca-appliance\" TARGET=\"_blank\">SCA Appliance</A>)</TD>";
	echo "<TD ALIGN=\"right\"><a href=\"https://www.suse.com/support/\" alt=\"SUSE Technical Support\" target=\"_blank\">SUSE Technical Support</a></td>";
	echo "</TR>\n</TABLE>\n";
	echo "</BODY>\n";
	echo "</HTML>\n";
?>

