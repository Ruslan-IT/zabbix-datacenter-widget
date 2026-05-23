<?php declare(strict_types = 1);

$form = new CWidgetFormView($data);

// Выводим все поля, которые были добавлены в WidgetForm, в порядке добавления
$fields_order = [
    'hostids',
    'header_title',
    'header_itemids_1', 'header_label_1',
    'header_itemids_2', 'header_label_2',
    'header_itemids_3', 'header_label_3',
    'header_itemids_4', 'header_label_4',
    'header_itemids_5', 'header_label_5',
    'header_itemids_6', 'header_label_6',
    'node_tag',
    'item_key_availability',
    'item_key_traffic',
    'item_key_temperature',
    'rack_width',
    'rack_height',
    'number_of_racks',
    'visual_theme',
    'card_language',
    'panel_scale'
];

foreach ($fields_order as $field_name) {
    if (isset($data['fields'][$field_name])) {
        $field = $data['fields'][$field_name];
        // Для MultiSelectItem нужно создать view и добавить в форму
        if (strpos($field_name, 'header_itemids_') === 0) {
            $form->addField(new CWidgetFieldMultiSelectItemView($field));
        } elseif ($field_name === 'hostids') {
            $form->addField(new CWidgetFieldMultiSelectHostView($field));
        } elseif (in_array($field_name, ['visual_theme', 'card_language', 'panel_scale'])) {
            $form->addField(new CWidgetFieldSelectView($field));
        } else {
            $form->addField(new CWidgetFieldTextBoxView($field));
        }
    }
}

// Добавляем JavaScript инициализации (если есть)
$widget_edit_js = file_get_contents(__DIR__.'/../assets/js/widget.edit.js');
if ($widget_edit_js !== false) {
    $form->addJavaScript($widget_edit_js);
}
$form->addJavaScript('window.switch_panel_widget_form.init();');

$form->show();