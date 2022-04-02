
jQuery(function ($) {
$('#submitbutton').click(function(){
$('input').each(function() {
$(this).removeAttr('disabled');
});
});
$('#resetbutton').click(function(){
$('input').each(function() {
$(this).removeAttr('disabled');
$(this).prop('checked', false );
});
});
$('document').ready(function() {
$('input').each(function() {
$(this).removeAttr('disabled');
});
});
$('input').change(function() {
$.foo_changed = $(this);
$('input').each(function() {
$(this).removeAttr('disabled');
});
if ($(this).attr('checked') == 'checked') {
$('input').each(function() {
$.foo_status = 'true';
if ($(this).val() == '2' && $.foo_changed.val() == '2') {
$.foo_status = 'false';
}
if ($(this).val() == '3' && $.foo_changed.val() == '2') {
$.foo_status = 'false';
}
if ($(this).val() == '2' && $.foo_changed.val() == '3') {
$.foo_status = 'false';
}
if ($(this).val() == '3' && $.foo_changed.val() == '3') {
$.foo_status = 'false';
}
if ($.foo_status == 'true') {
$(this).attr('disabled', true);
}
});
$.foo_selected = [];
$('input').each(function() {
if ($(this).is(':checked')) {
$.foo_selected.push($(this).val());
}
});
if ($.foo_changed.val() == '2') {
$('input').each(function() {
if ($(this).val() == '2') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('2' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('2',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '2') {
$(this).attr('disabled', true);
}
});
}
}
});
}
if ($.foo_changed.val() == '2') {
$('input').each(function() {
if ($(this).val() == '3') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('3' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('3',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '3') {
$(this).attr('disabled', true);
}
});
}
}
});
}
if ($.foo_changed.val() == '3') {
$('input').each(function() {
if ($(this).val() == '2') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('2' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('2',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '2') {
$(this).attr('disabled', true);
}
});
}
}
});
}
if ($.foo_changed.val() == '3') {
$('input').each(function() {
if ($(this).val() == '3') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('3' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('3',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '3') {
$(this).attr('disabled', true);
}
});
}
}
});
}
} else {
$.foo_one_selected = 'false';
$('input').each(function() {
if ($(this).is(':checked')) {
$.foo_one_selected = 'true';
}
});
if ($.foo_one_selected == 'true') {
$('input').each(function() {
$.foo_status = 'true';
if ($(this).val() == '2' && $.foo_changed.val() == '2') {
$.foo_status = 'false';
}
if ($(this).val() == '3' && $.foo_changed.val() == '2') {
$.foo_status = 'false';
}
if ($(this).val() == '2' && $.foo_changed.val() == '3') {
$.foo_status = 'false';
}
if ($(this).val() == '3' && $.foo_changed.val() == '3') {
$.foo_status = 'false';
}
if ($.foo_status == 'true') {
$(this).attr('disabled', true);
}
});
$.foo_selected = [];
$('input').each(function() {
if ($(this).is(':checked')) {
$.foo_selected.push($(this).val());
}
});
if ($.foo_changed.val() == '2') {
$('input').each(function() {
if ($(this).val() == '2') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('2' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('2',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '2') {
$(this).attr('disabled', true);
}
});
}
}
});
}
if ($.foo_changed.val() == '2') {
$('input').each(function() {
if ($(this).val() == '3') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('3' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('3',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '3') {
$(this).attr('disabled', true);
}
});
}
}
});
}
if ($.foo_changed.val() == '3') {
$('input').each(function() {
if ($(this).val() == '2') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('2' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('2',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '2') {
$(this).attr('disabled', true);
}
});
}
}
});
}
if ($.foo_changed.val() == '3') {
$('input').each(function() {
if ($(this).val() == '3') {
$.array_with_associated_tags_all = [["2"],["2"],["2","3"],["3"],["3"]];
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all, item_array_with_associated_tags_all) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP, item_arrayFromPHP) {
if ('3' == item_arrayFromPHP) {
jQuery.each(item_array_with_associated_tags_all, function(index_arrayFromPHP_inner, item_arrayFromPHP_inner) {
if (index_arrayFromPHP == index_arrayFromPHP_inner) {
jQuery.each($.foo_selected, function(index_selected, item_selected) {
if(jQuery.inArray(item_selected,item_array_with_associated_tags_all) == -1){;
$.array_with_associated_tags_all[index_array_with_associated_tags_all]=[];
}
});
}
});
}
});
});
$.foo_this_should_be_disables = 'true';
jQuery.each($.array_with_associated_tags_all, function(index_array_with_associated_tags_all_end, item_array_with_associated_tags_all_end) {
if(jQuery.inArray('3',item_array_with_associated_tags_all_end) > -1 ){;
$.foo_this_should_be_disables = 'false';
}
});
if ($.foo_this_should_be_disables == 'true') {;
$('input').each(function() {
if ($(this).val() == '3') {
$(this).attr('disabled', true);
}
});
}
}
});
}
} else {
}
}
});
});