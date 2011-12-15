<?php

$PluginInfo['VanillaTwitterEmbed'] = array (
	'Name'				=>	'Twitter Embed',
	'Description'			=>	'Embed tweets directly into posts and messages.',
	'Version'				=>	'0.1',
	'RequiredApplications'	=>	array('Vanilla' => '2.0.18'),
	'RequiredPlugins'		=>	FALSE,
	'HasLocale'			=>	FALSE,
	'SettingsUrl'			=>	'/dashboard/plugin/twitter-embed',
	'SettingsPermission'	=>	'Garden.Settings.Manage',
	'Author'				=>	'Jamie Chung',
	'AuthorEmail'			=>	'me@jamiechung.me',
	'AuthorUrl'			=>	'http://www.jamiechung.me'
);

class VanillaTwitterEmbedPlugin extends Gdn_Plugin 
{
	public function Setup()
	{
		
	}
	
	public function Enabled ()
	{
		return ( C('Plugins.TwitterEmbed.Enabled') == TRUE );
	}
	
	public function Controller_Toggle ( $Sender )
	{
		if ( Gdn::Session()->ValidateTransientKey(GetValue(1, $Sender->RequestArgs)) )
		{
			if ( C('Plugins.TwitterEmbed.Enabled') )
			{
				RemoveFromConfig('Plugins.TwitterEmbed.Enabled');
			}
			else
			{
				SaveToConfig('Plugins.TwitterEmbed.Enabled', TRUE);
			}
		}
		
		redirect('plugin/twitter-embed');
	}
}