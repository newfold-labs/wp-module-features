{
	const API_ENDPOINT = window.NewfoldRuntime.restUrl + 'newfold-features/v1';

	const attachToDOM = () => {
		window.Features = buildFeaturesObject();
		updateFeatures();
	};

	const buildFeaturesObject = () => {
		return {
			updateFeatures,
			isEnabled,
			enable,
			disable,
		};
	};

	const updateFeatures = async () => {
		const result = {};

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/features`,
				method: 'GET',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'features' ) ) {
					result.success = true;
					result.features = response.features;
					window.Features.features = response.features;
				} else {
					result.success = false;
					result.features = null;
				}
			} )
			.catch( () => {
				result.success = false;
				result.features = null;
			} );

		return result;
	};

	const isEnabled = async ( featureName ) => {
		const result = {};

		if (
			! window.hasOwnProperty( 'Features' ) ||
			! window.Features.hasOwnProperty( 'features' ) ||
			! window.Features.features.hasOwnProperty( featureName )
		) {
			return updateFeatures().then( () => {
				return isEnabled( featureName );
			} );
		}

		result.success = true;
		result[ `${ featureName }` ] = window.Features.features[ `${ featureName }` ];
		result.feature = {
			feature: featureName,
			isEnabled: window.Features.features[ `${ featureName }` ],
		};

		return result;
	};

	const enable = async ( featureName ) => {
		const result = {};

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/feature/enable?feature=${ featureName }`,
				method: 'POST',
				data: {
					feature: featureName,
				},
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'feature' ) ) {
					result.success = true;
					window.Newfold.features[ `${ featureName }` ] = true;
				} else {
					result.success = false;
				}
			} )
			.catch( () => {
				result.success = false;
			} );

		return result;
	};

	const disable = async ( featureName ) => {
		const result = {};

		await window.wp
			.apiFetch( {
				url: `${ API_ENDPOINT }/feature/disable?feature=${ featureName }`,
				method: 'POST',
			} )
			.then( ( response ) => {
				if ( response.hasOwnProperty( 'comingSoon' ) ) {
					result.success = true;
					window.Newfold.features[ `${ featureName }` ] = false;
				} else {
					result.success = false;
				}
			} )
			.catch( () => {
				result.success = false;
			} );

		return result;
	};

	window.addEventListener( 'DOMContentLoaded', () => {
		attachToDOM();
	} );
}
