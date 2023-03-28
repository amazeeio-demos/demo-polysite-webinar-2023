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

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cluster = getenv("LAGOON_KUBERNETES") ? getenv("LAGOON_KUBERNETES") : "local";	  
    $project = getenv("LAGOON_PROJECT") ? getenv("LAGOON_PROJECT") : "local";	  
    $environment = getenv("LAGOON_ENVIRONMENT") ? getenv("LAGOON_ENVIRONMENT") : "local";	  
    $environment_type = getenv("LAGOON_ENVIRONMENT_TYPE") ? getenv("LAGOON_ENVIRONMENT_TYPE") : "local";	  
    $version = file_get_contents("/app/aio-world-ver.txt");

    return [
	    '#markup' => '<div>' . 
	    	"<div><u><b>amazee.io information</b></u></div>" . 
	    	"<div>Cluster: ".$cluster."</div>" .
	    	"<div>Project: ".$project."</div>" . 
		"<div>Environment: ".$environment." (".$environment_type.")</div>" . 
		"<div>AIO World Version: ".$version."</div>" . 
		"</div>"
    ];
  }

}

