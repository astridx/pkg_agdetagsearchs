/**
 * @subpackage  Agdetagsearch
 *
 * @copyright   Copyright (C) 2017 Astrid Günther & Dimitry Engbert All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
jQuery(function ($) {
$(function(){
    $('input[type="radio"]').click(function(){

        var $radio = $(this);

        // if this was previously checked
        if ($radio.data('waschecked') == true)
        {
            $radio.prop('checked', false);
            $radio.data('waschecked', false);
        }
        else
		{
            $radio.data('waschecked', true);
		}

        // remove was checked from other radios / siblings is not easy posible :(
		$('input[type="radio"]').each(function() {
			if($(this).is(':checked')) {
				$(this).data('waschecked', true);
			}
			else
			{
				$(this).data('waschecked', false);
			}
		});

		$(this).trigger('change');
    });
});
});
