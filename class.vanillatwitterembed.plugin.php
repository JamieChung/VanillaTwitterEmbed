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

class VanillaTwitterEmbedPlugin implements Gdn_IPlugin 
{
	private $twitterStatusRegex = '/(http|https)\:\/\/twitter.com\/(.*?)\/status\/([0-9]+)/';
	
	public function Setup()
	{
		
	}
	
	protected function CreateEmbed ( $matches )
	{
		$id = $matches[3];
		$api = 'http://api.twitter.com/1/statuses/oembed.json?id='.$id;
		$response = file_get_contents($api);
		if ( !$response )
		{
			return false;
		}
		
		$response = json_decode($response);
		return $response->html;
	}
	
	protected function TwitterEmbed ( $content )
	{
		$this->content = $content;
		$content = preg_replace_callback($this->twitterStatusRegex, array($this, 'CreateEmbed'), $content);
		
		
		return $content;
	}
	
	    /**
     * DiscussionController_BeforeCommentBody_Handler
     * @param DiscussionController $Sender
     */
    public function DiscussionController_BeforeCommentBody_Handler(&$Sender) {
        $Sender->EventArguments['Comment']->Body = $this->TwitterEmbed($Sender->EventArguments['Comment']->Body);
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