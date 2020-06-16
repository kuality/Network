<?php

declare( strict_types = 1 );

namespace MediaWiki\Extension\Network\NetworkFunction;

class NetworkUseCase {

	public function __construct() {
	}

	public function run( RequestModel $request ): ResponseModel {
		$response = new ResponseModel();

		$keyValuePairs = $this->parserArgumentsToKeyValuePairs( $request->functionArguments );

		$response->pageNames = $this->getPageNames( $request );
		$response->cssClass = $this->getCssClass( $keyValuePairs );

		return $response;
	}

	private function parserArgumentsToKeyValuePairs( array $arguments ): array {
		$pairs = [];

		foreach ( $arguments as $argument ) {
			[$key, $value] = $this->argumentStringToKeyValue( $argument );

			if ( !is_null( $key ) ) {
				$pairs[$key] = $value;
			}
		}

		return $pairs;
	}

	private function argumentStringToKeyValue( string $argument ): array {
		if ( false === strpos( $argument, '=' ) ) {
			return [null, $argument];
		}

		[$key, $value] = explode( '=', $argument );
		return [trim($key), trim($value)];
	}

	private function getPageNames( RequestModel $request ): array {
		$pageNames = [];

		foreach ( $request->functionArguments as $argument ) {
			[$key, $value] = $this->argumentStringToKeyValue( $argument );

			if ( $value !== '' && ( is_null( $key ) || $key === 'page' ) ) {
				$pageNames[] = $value;
			}
		}

		if ( $pageNames === [] ) {
			$pageNames[] = $request->renderingPageName;
		}

		return $pageNames;
	}

	private function getCssClass( array $arguments ): string {
		return trim( 'network-visualization ' . ( trim( $arguments['class'] ?? '' ) ) );
	}

}
