@echo off
rem Copy files in our folder structure to development installation.
del D:\Projecten\Acumulus\OpenCart\www23\www\admin\controller\extension\module\acumulus_customise_invoice.php 2> nul
mklink /H D:\Projecten\Acumulus\OpenCart\www23\www\admin\controller\extension\module\acumulus_customise_invoice.php D:\Projecten\Acumulus\Webkoppelingen\OpenCart23\acumulus_customise_invoice.ocmod\upload\admin\controller\extension\module\acumulus_customise_invoice.php
del D:\Projecten\Acumulus\OpenCart\www23\www\admin\language\en-gb\extension\module\acumulus_customise_invoice.php 2> nul
mklink /H D:\Projecten\Acumulus\OpenCart\www23\www\admin\language\en-gb\extension\module\acumulus_customise_invoice.php D:\Projecten\Acumulus\Webkoppelingen\OpenCart23\acumulus_customise_invoice.ocmod\upload\admin\language\en-gb\extension\module\acumulus_customise_invoice.php
del D:\Projecten\Acumulus\OpenCart\www23\www\admin\view\template\extension\module\acumulus_customise_invoice.tpl 2> nul
mklink /H D:\Projecten\Acumulus\OpenCart\www23\www\admin\view\template\extension\module\acumulus_customise_invoice.tpl D:\Projecten\Acumulus\Webkoppelingen\OpenCart23\acumulus_customise_invoice.ocmod\upload\admin\view\template\extension\module\acumulus_customise_invoice.tpl
