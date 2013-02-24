#!/bin/bash

# ----------------------------------------------------------------------
# PHP version 5
# ----------------------------------------------------------------------
# Copyright (c) 1997-2010 The Authors
# ----------------------------------------------------------------------
# http://opensource.org/licenses/bsd-license.php New BSD License
# ----------------------------------------------------------------------
#  Authors:     Alexander Merz (alexmerz@php.net)
# ----------------------------------------------------------------------
#
#  Last updated 12/29/2004 ($Id$ is not replaced if the file is binary)
#
# change this lines to match the paths of your system
# -------------------

main(){
    # Test to see if this is a raw pear.bat (uninstalled version)
    TMPTMPTMPTMPT=@includ
    PMTPMTPMT=${TMPTMPTMPTMPT}e_path@

    for x in /c/xampp/php/pear/*
    do
        if [ "$x" == "$PMTPMTPMT" ]; then
            not_installed;
        fi
    done
    
    # Check PEAR global ENV, set them if they do not exist
    if [ "${PHP_PEAR_INSTALL_DIR}"=="" ]; then 
        PHP_PEAR_INSTALL_DIR='/c/xampp/php/pear'
    fi
    
    if [ "${PHP_PEAR_BIN_DIR}"=="" ]; then 
        PHP_PEAR_BIN_DIR='/c/xampp/php'
    fi
    
    if [ "${PHP_PEAR_PHP_BIN}"=="" ]; then
        PHP_PEAR_PHP_BIN="/c/xampp/php/./php"
    fi

    installed 
}

installed () {
    run
}

run () {
    "${PHP_PEAR_PHP_BIN}" -C -d date.timezone=UTC -d output_buffering=1 -d safe_mode=0 -d open_basedir="" -d auto_prepend_file="" -d auto_append_file="" -d variables_order=EGPCS -d register_argc_argv="On" -d "include_path='${PHP_PEAR_INSTALL_DIR}'" -f "${PHP_PEAR_INSTALL_DIR}\pearcmd.php" -- $1 $2 $3 $4 $5 $6 $7 $8 $9
}

main
