/**
 * Features JS API
 * JS interface for the features module and feature management
 */
{
	/**
	 * Helper method to check if a feature is enabled by name
	 *
	 * @param {*} name
	 * @return {boolean} wether the features is enabled
	 */
	const isEnabled = async ( name ) => {
		return window.NewfoldFeatures.features[ name ];
	};

	/**
	 * Helper method to enable a feature by name
	 * @param {*} name
	 * @return {*} {success:boolean and message:string}
	 */
	const enable = async ( name ) => {
		const result = {};
		if ( await isEnabled( name ) ) {
			result.success = false;
			result.message = `'${ name }' is already enabled.`;
			return result;
		}

		await window.wp
			.apiFetch( {
				url: `${ window.NewfoldFeatures.restUrl }/feature/enable?feature=${ name }`,
				method: 'POST',
			} )
			.then( ( response ) => {
				console.log(response);
				if ( response === true ) {
					updateFeature( name, true );
					result.success = true;
					result.message = `'${ name }' is now enabled`;
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

	/**
	 * Helper method to disable a feature by name
	 * @param {*} name
	 * @return {*} {success:boolean and message:string}
	 */
	const disable = async ( name ) => {
		const result = {};
		if ( ! ( await isEnabled( name ) ) ) {
			result.success = false;
			result.message = `'${ name }' is already disabled.`;
			return result;
		}

		await window.wp
			.apiFetch( {
				url: `${ window.NewfoldFeatures.restUrl }/feature/disable?feature=${ name }`,
				method: 'POST',
			} )
			.then( ( response ) => {
				// console.log(response);
				if ( response === true ) {
					updateFeature( name, false );
					result.success = true;
					result.message = `'${ name }' is now disabled`;
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

	/**
	 * Update a feature in the object
	 * @param {string}  name
	 * @param {boolean} isEnabled
	 */
	const updateFeature = ( name, isEnabled ) => {
		window.NewfoldFeatures.features[ name ] = isEnabled;
	};

	// Set up the localized features object with methods
	const attachMethodsToObject = () => {
		const methods = {
			isEnabled,
			enable,
			disable,
		};
		window.NewfoldFeatures = { ...window.NewfoldFeatures, ...methods };
	};

	// Attach methods when DOM is ready
	window.addEventListener( 'DOMContentLoaded', () => {
		attachMethodsToObject();
	} );
}
