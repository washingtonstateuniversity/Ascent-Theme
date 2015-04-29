(function($,window){
	$(document).ready(function(){
		if ( 0 !== $('#ascent-assign-advisors').length) {
			window.wsuwp_uc_autocomplete_object( 'faa_advisors' );
			$('#faa_advisors-results').on('click', '.uc-object-close','faa_advisors',window.wsuwp_uc_remove_object);
		}
		if ( 0 !== $('#ascent-assign-project-leads').length) {
			window.wsuwp_uc_autocomplete_object( 'project_leads' );
			$('#project_leads-results').on('click', '.uc-object-close','project_leads',window.wsuwp_uc_remove_object);
		}
	});
}(jQuery,window));