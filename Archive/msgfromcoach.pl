#!/usr/bin/perl -w
# Process email sent by Fred Shaw to Coach@applitec.com
# NOTE: permissions are importand and ALSO we must put a link in /etc/smrsh
# The messages must go in a directory where 'mail' has write permision!
# I have made a directory under /swam called swam-messages and made it owned by
# 'barton.mail' and set permissions to '775' (it could be 665 too). This
# lets 'mail' create, write, and delete the 'coach.txt' file.

use strict;

umask(0);

my $filename = "/swam/swam-messages/coach.txt";

open(COACH, ">$filename") || die("error opening: $!");

my $command;
my $start;

while(<>) {
  if(/Subject:(.*)/) {
    
    $command = $1;
    chomp($command);

    # allow del, Del, Delete, dele, etc. Must be at least del
    
    if($command =~ /[dD][eE][lL][eE]?[tT]?[eE]?/) {
      print "doing delete\n";
      
      close(COACH);
      unlink($filename) || die("error unlinking: $!");
      exit();
    }
  }

  if(/^$/) {
    $start = 1;
    next;
  }
  next if !$start;

  print COACH "$_";
}

close(COACH);

