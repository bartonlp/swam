#!/usr/bin/perl -w
# Process email sent by Fred Shaw to Coach@applitec.com

use strict;

umask(0);

my $filename = "/swam/swam-messages/msginsert1.txt";

open(COACH, ">$filename") || die("error opening: $!");

my $command;
my $start;

while(<>) {
  if($_ =~ /Subject:(.*)/) {
    
    $command = $1;
    chomp($command);
    
    if($command =~ /[dD][eE][lL][eE][tT]/) {
      close(COACH);
      unlink($filename);
      exit();
    }
  }

  if($_ eq "\n") {
    $start = 1;
    next;
  }
  next if !$start;

  print COACH "$_";
}

close(COACH);

