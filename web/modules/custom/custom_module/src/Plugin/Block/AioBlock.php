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
  	'uk' => 'uk.png',
  	'de' => 'de.png',
  	'ch' => 'ch.png',
  	'us' => 'us.png',
  	'fi' => 'fi.png',
  	'eu' => 'eu.png',
  	'lo' => 'lo.png',
  ];

  protected $countries = [
  	'uk' => 'UK',
  	'de' => 'Germany',
  	'ch' => 'Switzerland',
  	'us' => 'USA',
  	'fi' => 'Finland',
  	'eu' => 'Europe',
  	'lo' => 'Local',
  ];

  protected $logos = [
  	'uk3' => 'aws.png',
  	'de3' => 'aws.png',
  	'fi2' => 'gcp.png',
  	'us2' => 'aws.png',
  	'us3' => 'gcp.png',
  	'ch4' => 'gcp.png',
  	'dkr' => 'dkr.png',
  ];

  protected $providers = [
  	'uk3' => 'AWS',
  	'de3' => 'AWS',
  	'fi2' => 'GCP',
  	'us2' => 'AWS',
  	'us3' => 'GCP',
  	'ch4' => 'GCP',
  	'dkr' => 'Docker',
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
    $region = "Local";
    if(preg_match("/^(\w\w)\d.amazee.io$/", $cluster, $MAT)) {
	    $flag = '<img class="custom-module-flag" src="/' . $flagPath . "/" . $this->flags[$MAT[1]] . '" />';
	    $region = $this->countries[$MAT[1]];
    } else if($cluster == "local") {
	$flag = '<img class="custom-module-flag" src="/' . $flagPath . "/" . $this->flags['lo'] . '" />';
    }

    $logo = "";
    $infra = "";
    if(preg_match("/^(\w\w\d).amazee.io$/", $cluster, $MAT)) {
	$logo = '<img class="custom-module-logo" src="/' . $logoPath . "/" . $this->logos[$MAT[1]] . '" />';
	$infra = $this->providers[$MAT[1]];
    } else if($cluster == "local") {
	$logo = '<img class="custom-module-logo" src="/' . $logoPath . "/" . $this->logos['dkr'] . '" />';
	$infra = "Docker";
    }

    $aio = '<img class="custom-module-aio-logo" src="/' . $logoPath . '/amazee-io-mirantis.svg" />';
    return [
	    '#attached'=> ['library' => ['custom_module/custom_module']],
	    '#markup' => '<div class="aio-info">' . 
		"<div class='aio-info-part-modver'>" . 
	  	  "<span class='aio-info-part'><span class='aio-info-part-title'>Module Version:</span> ".$version."</span>" . 
		"</div>" . 
		"<div>" . 
	    	  "<span class='aio-info-logo'>$aio</span>" . 
	    	  "<span class='aio-info-part'>$logo ($infra)</span>" .
	    	  "<span class='aio-info-part'>$flag ($region)</span>" .
	    	  "<span class='aio-info-part'><span class='aio-info-part-title'>Project:</span> ".$project."</span>" . 
		  "<span class='aio-info-part'><span class='aio-info-part-title'>Environment:</span> ".$environment." (".$environment_type.")</span>" . 
		"</div>" . 
	      "</div>"
    ];
  }

}

