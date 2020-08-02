<?php

namespace App\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use App\Form\PreinscriptionAdresseType;
use App\Form\PreinscriptionCompteType;

class PreinscriptionFlow extends FormFlow {

	protected function loadStepsConfig() {
		return [
			[
				'label' => 'Mon compte sur bourges.avironclub.fr',
				'form_type' => PreinscriptionCompteType::class,
			],
			[
				'label' => 'Mon identité',
				'form_type' => PreinscriptionIdentiteType::class
			],
			[
				'label' => 'Mes contacts',
				'form_type' => PreinscriptionContactsType::class
			],
			[
				'label' => 'Mes envies',
				'form_type' => PreinscriptionEnviesType::class
			],
		];
	}

}