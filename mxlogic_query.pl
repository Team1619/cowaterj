#!/usr/local/bin/perl
use Getopt::Std;

# ------------------------------------------------------------------------------
# Initialization
# ------------------------------------------------------------------------------

# Environment ##################################################################

# Modules

use AB_Env       qw( setDebug );

#setDebug( 3,3,3 ); # DEBUG levels

# Imported modules #############################################################

# Native -----------------------------------------------------------------------

#use strict;
#use warnings;

#use CGI             qw( :standard );

# Include ----------------------------------------------------------------------

# STD_LIB
use Logs                qw( libLog scriptLog traceLog );

# AB_LIB

use AB_Access 	qw ( hasKey );
use AB_Domains  qw ( checkDomainSyntax );
use AB_Lang		qw ( getLangTag );
use AB_Env		qw ( failed FAILURE SUCCESS );

# DB_LIB

use DB_Connect	qw( DB_Connect DB_Disconnect );
use DB_Env		qw( DB_STDAPP );
use DB_Product	qw ( selectPlanProducts );
use DB_Users	qw ( countUserOld selectUserFile );
	
# OBJ_LIB

use MailFilter;
use MailFilter::Aliases;
use MailFilter::Move;

use User;

use String qw ( search_and_replace);

use VWH::Env ':all';
use VWH::DA::Library::Transaction;

# Variables ####################################################################

our (
    %in,
    %Views,
#	%Actions,	
    #$CGI,
    $dbh,
    $REMOTE_USER,
    $REMOTE_USER_OBJ,
	$result,
	$whiteListValues,
	$blackListValues,
	$podSettings,
	$aliases
);

# Initialization ###############################################################


        my %opt;
        my $domain;
        getopts('d:h', \%opt);
        if($opt{'d'}) {
                $domain = $opt{'d'};
        }
        if ($opt{'h'}) {
		print "must use -d domain\n";
                exit;
        }


# Script Configuration ########################################################

$in{view}       ||= q{main};     # Default view.

# Initialize logs
scriptLog( 1, 0, qq{******** BEGINNING MAIN EXECUTION TIME ********} );
traceLog( 0, qq{******** BEGINNING MAIN EXECUTION TIME ********} );

################################################################################
# ------------------------------------------------------------------------------
# ------------------------------------------------------------------------------
# Script
# ------------------------------------------------------------------------------
# ------------------------------------------------------------------------------


#-------------------------------------------------------------------------------
$dbh = DB_Connect( GROUP_ID, DB_STDAPP );
if (!defined($dbh))
{
    # Unable to brand this or do in a better fashion, since I cant get to DB.
    clean_exit( qq{Cant Connect to Database} );
}
#-------------------------------------------------------------------------------

#-------------------------------------------------------------------------------

#------------------------------------------------------------------------------
$result = &get_pod_info;
#------------------------------------------------------------------------------

#------------------------------------------------------------------------------
# Lookup table of Views that can be accessed
%Views =
(
	main    		=> \&main,
	podinfo			=> \&podInfo
);

( $in{view} && $Views{$in{view}} ) ? $Views{$in{view}}->() : $Views{'main'}->();
#------------------------------------------------------------------------------

# ------------------------------------------------------------------------------
# Termination
# ------------------------------------------------------------------------------

scriptLog( 1, 0, q{******** ENDING MAIN EXECUTION  ********} );
traceLog( 1, q{******** ENDING MAIN EXECUTION  ********} );

clean_exit();

# END Main #####################################################################

#------------------------------------------------------------------------------
sub podInfo 
{
	my $content;
	my $cssTag;
	my $podInfoPage;

	$cssTag = AB_Lang::getLangTag('nav', '2', $REMOTE_USER_OBJ, $dbh);
	$content = AB_Lang::getLangTag('tools', '4', $REMOTE_USER_OBJ, $dbh);

	if ($result eq "1")
	{
		$result = "Success";
	}
	else
	{
		$result = errors($result);
	}

	$podInfoPage = search_and_replace($content->{text}, {
														css=>$cssTag->{text},
														domain=>$podSettings->{detail}->{domain},
														result=>$result,
														spamon=>$podSettings->{on},
														spamsetting=>$podSettings->{setting},
														viruson=>$podSettings->{on_virus},
														virusscan=>$podSettings->{setting_virus},
														group=>$podSettings->{detail}->{customer},
														created=>$podSettings->{detail}->{created},
														whiteList=>$whiteListValues,
														blackList=>$blackListValues,
														aliases=>$aliases
													});
	print $podInfoPage;
	#print $whiteListValues;
	#print "made it here";

	return 1;

}

#------------------------------------------------------------------------------
sub main
{

	my $content;
	my $cssTag;
	my $mainPage;

	$cssTag = AB_Lang::getLangTag('nav', '2', $REMOTE_USER_OBJ, $dbh);
	$content = AB_Lang::getLangTag('tools', '3', $REMOTE_USER_OBJ, $dbh);

	if ($result eq "1")
	{
		$result = 'SUCCESS';
	} 
	elsif (defined($result))
	{
		$result = errors($result);
	}
	
	$mainPage = search_and_replace($content->{text}, {
														css=>$cssTag->{text},
														message=>$result
													});
	print $mainPage;

	return 1;

} # END main

#------------------------------------------------------------------------------
sub get_pod_info 
{
	my $FilterSettings;
	my $whiteListEntries;
	my $blackListEntries;
	
	my ($Filter) = new MailFilter();

	# Get domain
	print $domain;
	print "\n";
	my ($result) = $Filter->GetDomain( site_domain => $domain );
	if ($result eq SUCCESS)
	{
   		$podSettings = $Filter->Result( reset=>1 );
		if (!$podSettings->{exists})
		{
			libLog(0,1006,errors(1006));
			return 1006;
		}
		my ($result) = $Filter->GetFilterSettings( site_domain => $domain );
		$FilterSettings = $Filter->Result( remove=>1 );
		foreach my $key (keys %$FilterSettings)
		{	
			$podSettings->{$key} = $FilterSettings->{$key};
		}

		my $blackList = $Filter->GetBlacklist(site_domain=>$domain);
		$blackListEntries = $Filter->Result( remove=>1 );
		print "black list : ";
		foreach my $ble (@$blackListEntries)
		{
			#$blackListValues .= $ble . qq{<BR>};
			$blackListValues = $ble;
			print $blackListValues;
			print ";";
		}

		my $whiteList = $Filter->GetWhitelist(site_domain=>$domain);
		$whiteListEntries = $Filter->Result(remove=>1);
		print "\n";
		print "white list : ";
		foreach my $wle (@$whiteListEntries)
		{
			#$whiteListValues .= $wle . qq{<BR>};
			$whiteListValues = $wle;
			print $whiteListValues;
			print ";";
		}

		my ($Filter) = new MailFilter::Aliases();
		($result) = $Filter->GetAliases( site_domain => $domain );
		if (failed($result))
		{
			libLog(0,1009,errors(1009));
			return 1009;
		}
		my $list = $Filter->Result( remove=>1 );
		foreach my $element (@$list)
		{
			$aliases .= $element . qq{<BR>};
		}	
	}
	else
	{
		libLog(0,1006,errors(1006));
		return 1006;
	}
	print "\n";

	return 1;

} # END of get_pod_info 

#------------------------------------------------------------------------------
sub clean_exit
{
    my ( $msg, $want_header ) = @_;

    if ( $msg )
    {
        print $msg;
    	scriptLog( 0, 1, $msg );
    }

    DB_Disconnect( $dbh );

    undef %in;
    undef $dbh;
    undef $REMOTE_USER;
    undef $REMOTE_USER_OBJ;
	undef $result;
	undef $podSettings;
	undef $whiteListValues;
	undef $blackListValues;
	undef $aliases;

    exit 0;

} # END clean_exit


# ------------------------------------------------------------------------------
sub errors
{
	# Parameters

	my $error	= shift; # Numeric error

	# Variables

	my %errorCodes;

	%errorCodes =
	(
		1000 => "Domain does not exist in userfile",
		1001 => "Domain Syntax Invalid.",
		1002 => "Error adding Domain to the Pod.",
		1003 => "Error removing Domain from the Pod.",
		1004 => "Cannot remove from the Pod, user is not in userfile_old",
		1005 => "Cannot remove from the Pod, Transactions Pending.",
		1006 => "Domain is not in the Pod.",
		1007 => "Domain Alias Syntax Invalid",	
		1008 => "Failure to Add Domain Alias",	
		1009 => "Failure to get Domain Aliases",	
		1010 => "The plan this user is on does NOT support MXLogic.",	
		1011 => "Did not check terms required to process a Move",	
		1012 => "Failure to Move Userid to new group",	
		1013 => "Userid does not exist on source group",	
		1014 => "Failure to remove Spam settings from Source Group",	
		1015 => "Destination group userid not supplied for move",	
		1016 => "Failure to remove Domain Alias",
	);

	# Script ###################################################################

	return ($errorCodes{$error});

} # END errors
