<?PHP
	if(isset($_SERVER['HTTP_USER_AGENT']))
	{
		include 'checklogin.php';
	}
?>

<?PHP
	$User = 'sdbroker';
	$Password = "${User}_password";
	$Database = 'ServerDiagnostics';
	$SCDIAG_HOME = '/usr/lib/sca/';
	$DBHost = 'localhost';
	$ResourceRefresh = 300;
	$StatsRefresh = 15;
	$StatusRefresh = 2;
	$SRView = 0; // 0=NCC, 1=Novell

	echo "<!-- Config: User              = $User -->\n";
	echo "<!-- Config: Database          = $Database -->\n";
	echo "<!-- Config: SCDIAG_HOME       = $SCDIAG_HOME -->\n";
	echo "<!-- Config: DBHost            = $DBHost -->\n";
	echo "<!-- Config: ResourceRefresh   = $ResourceRefresh -->\n";
	echo "<!-- Config: StatsRefresh      = $StatsRefresh -->\n";
	echo "<!-- Config: StatusRefresh     = $StatusRefresh -->\n";
	echo "<!-- Config: SRView            = $SRView -->\n\n";
?>
