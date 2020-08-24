<?php

namespace App\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use App\Form\PreinscriptionAttestationType;
use App\Form\PreinscriptionContactsType;
use App\Form\PreinscriptionEnviesType;
use App\Form\PreinscriptionIdentiteType;

class ReinscriptionFlow extends FormFlow {

	protected function loadStepsConfig() {
		return [
			[
				'label' => 'Mon identité',
				'form_type' => PreinscriptionIdentiteType::class
			],
			[
				'label' => 'Mes contacts',
				'form_type' => PreinscriptionContactsType::class
			],
			[
				'label' => 'Attestation',
				'form_type' => PreinscriptionAttestationType::class
			],
			[
				'label' => 'Mes envies',
				'form_type' => PreinscriptionEnviesType::class
			],
		];
	}
}