<?php

namespace Drupal\custom_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Aio' Block.
 *
 * @Block(
 *   id = "aio_block",
 *   admin_label = @Translation("Aio block"),
 *   category = @Translation("Aio World"),
 * )
 */
class AioBlock extends BlockBase {

  protected $flags = [
  	'de' => 'de.png',
  	'ch' => 'ch.png',
  	'us' => 'us.png',
  	'fi' => 'fi.png',
  	'eu' => 'eu.png',
  	'lo' => 'lo.png',
  ];

  protected $logos = [
  	'de3' => 'aws.png',
  	'fi2' => 'gcp.png',
  	'us2' => 'aws.png',
  	'us3' => 'gcp.png',
  	'ch4' => 'gcp.png',
  	'dkr' => 'dkr.png',
  ];

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cluster = getenv("LAGOON_KUBERNETES") ? getenv("LAGOON_KUBERNETES") : "local";	  
    $project = getenv("LAGOON_PROJECT") ? getenv("LAGOON_PROJECT") : "local";	  
    $environment = getenv("LAGOON_ENVIRONMENT") ? getenv("LAGOON_ENVIRONMENT") : "local";	  
    $environment_type = getenv("LAGOON_ENVIRONMENT_TYPE") ? getenv("LAGOON_ENVIRONMENT_TYPE") : "local";	  
    $version = file_get_contents("/app/aio-world-ver.txt");

    $logoPath = \Drupal::service('extension.list.module')->getPath('custom_module') . "/logos/";
    $flagPath = \Drupal::service('extension.list.module')->getPath('custom_module') . "/flags/";

    $flag = "";
    if(preg_match("/$(\w\w)\d\..amazee\.io$/", $cluster, $MAT)) {
	$flag = '<img class="custom-module-flag" src="/' . $flagPath . "/" . $this->flags[$MAT[1]] . '" />';
    } else if($cluster == "local") {
	$flag = '<img class="custom-module-flag" src="/' . $flagPath . "/" . $this->flags['lo'] . '" />';
    }

    $logo = "";
    if(preg_match("/$(\w\w\d)\..amazee\.io$/", $cluster, $MAT)) {
	$logo = '<img class="custom-module-logo" src="/' . $logoPath . "/" . $this->logos[$MAT[1]] . '" />';
    } else if($cluster == "local") {
	$logo = '<img class="custom-module-logo" src="/' . $logoPath . "/" . $this->logos['dkr'] . '" />';
    }

    return [
	    '#attached'=> ['library' => ['custom_module/custom_module']],
	    '#markup' => '<div>' . 
	    	"<div><u><b>amazee.io information</b></u></div>" . 
	    	"<div>Cluster: ".$cluster." $flag $logo</div>" .
	    	"<div>Project: ".$project."</div>" . 
		"<div>Environment: ".$environment." (".$environment_type.")</div>" . 
		"<div>AIO World Version: ".$version."</div>" . 
		"</div>"
    ];
  }

}

