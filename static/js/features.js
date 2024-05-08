{
	const API_ENDPOINT = window.NewfoldRuntime.restUrl + 'newfold-features/v1';
	const OBJECT_NAME = 'NewfoldFeatures';

	const attachToDOM = () => {
		window[ OBJECT_NAME ] = createFeaturesObject();
		updateFeatures();
	};

	const createFeaturesObject = () => {
		return {
			updateFeatures,
			isEnabled,
			enable,
			disable,
		};
	};

	const updateFeatures = async () => {
		let result = {};

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/features`,
				method: 'GET',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'features' ) ) {
					result = response.features;
					// save internally
					window[ OBJECT_NAME ].features = result;
				} else {
					result = false;
				}
			} )
			.catch( () => {
				result = null;
			} );

		return result;
	};

	const updateFeature = ( featureName, isEnabled ) => {
		window[ OBJECT_NAME ].features[ featureName ] = isEnabled;
	};

	const isEnabled = async ( featureName ) => {
		if (
			! window.hasOwnProperty( OBJECT_NAME ) ||
			! window[ OBJECT_NAME ].hasOwnProperty( 'features' ) ||
			! window[ OBJECT_NAME ].features.hasOwnProperty( featureName )
		) {
			return updateFeatures().then( () => {
				return isEnabled( featureName );
			} );
		}
		return window[ OBJECT_NAME ].features[ featureName ];
	};

	const enable = async ( featureName ) => {
		const result = {};
		if ( await isEnabled( featureName ) ) {
			result.success = false;
			result.message = `${ featureName } already enabled.`;
			return result;
		}

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/feature/enable?feature=${ featureName }`,
				method: 'POST',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'feature' ) ) {
					result[ response.feature ] = response.isEnabled;
					updateFeature( response.feature, response.isEnabled );
				} else {
					result.success = false;
					result.message = 'There was an unexpected error.';
				}
			} )
			.catch( ( error ) => {
				result.success = false;
				result.message = error.message;
			} );

		return result;
	};

	const disable = async ( featureName ) => {
		const result = {};
		if ( ! ( await isEnabled( featureName ) ) ) {
			result.success = false;
			result.message = `${ featureName } already disabled.`;
			return result;
		}

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/feature/disable?feature=${ featureName }`,
				method: 'POST',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'feature' ) ) {
					result[ response.feature ] = response.isEnabled;
					updateFeature( response.feature, response.isEnabled );
				} else {
					result.success = false;
					result.message = 'There was an unexpected error.';
				}
			} )
			.catch( ( error ) => {
				result.success = false;
				result.message = error.message;
			} );

		return result;
	};

	window.addEventListener( 'DOMContentLoaded', () => {
		attachToDOM();
	} );
}
