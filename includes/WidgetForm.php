<?php declare(strict_types = 1);

namespace Modules\DataCenterWidget\Includes;

use Zabbix\Widgets\CWidgetForm;
use Zabbix\Widgets\Fields\CWidgetFieldMultiSelectHost;
use Zabbix\Widgets\Fields\CWidgetFieldMultiSelectItem;
use Zabbix\Widgets\Fields\CWidgetFieldSelect;
use Zabbix\Widgets\Fields\CWidgetFieldTextBox;

class WidgetForm extends CWidgetForm {
    private const SOURCE_MANUAL = 0;
    private const SOURCE_ITEM = 1;
    private const MAX_HEADER_ITEMS = 6;

    public function addFields(): self {
        // Выбор хоста (опционально)
        $this->addField(
            (new CWidgetFieldMultiSelectHost('hostids', _('Host (optional)')))
                ->setMultiple(false)
        );

        // Поля для шапки виджета
        $this->addField(
            (new CWidgetFieldTextBox('header_title', _('Header title')))
                ->setDefault('ЦОД: Сводка')
        );

        for ($i = 1; $i <= self::MAX_HEADER_ITEMS; $i++) {
            $this->addField(
                (new CWidgetFieldMultiSelectItem('header_itemids_' . $i, sprintf(_('Header item #%d'), $i)))
                    ->setMultiple(false)
            );
            $this->addField(
                (new CWidgetFieldTextBox('header_label_' . $i, sprintf(_('Header label #%d'), $i)))
                    ->setDefault('')
            );
        }

        // Идентификация узлов
        $this->addField(
            (new CWidgetFieldTextBox('node_tag', _('Node tag (host tag)')))
                ->setDefault('role:node')
        );

        // Ключи элементов на каждый узел
        $this->addField(
            (new CWidgetFieldTextBox('item_key_availability', _('Availability item key pattern')))
                ->setDefault('icmp.ping[*]')
        );
        $this->addField(
            (new CWidgetFieldTextBox('item_key_traffic', _('Traffic item key pattern')))
                ->setDefault('net.if.in[*]')
        );
        $this->addField(
            (new CWidgetFieldTextBox('item_key_temperature', _('Temperature item key pattern')))
                ->setDefault('sensor.temp[*]')
        );

        // Настройка сетки (используем TextBox вместо IntegerBox для совместимости)
        $this->addField(
            (new CWidgetFieldTextBox('rack_width', _('Rack width (columns)')))
                ->setDefault('5')
        );
        $this->addField(
            (new CWidgetFieldTextBox('rack_height', _('Rack height (rows)')))
                ->setDefault('6')
        );
        $this->addField(
            (new CWidgetFieldTextBox('number_of_racks', _('Number of racks')))
                ->setDefault('10')
        );

        // Общие настройки
        $this->addField(
            (new CWidgetFieldSelect('visual_theme', _('Theme'), [
                0 => _('Follow Zabbix'),
                1 => _('Light'),
                2 => _('Dark')
            ]))->setDefault(0)
        );
        $this->addField(
            (new CWidgetFieldSelect('card_language', _('Card language'), [
                0 => _('Follow Zabbix'),
                1 => _('Chinese'),
                2 => _('English')
            ]))->setDefault(0)
        );
        $this->addField(
            (new CWidgetFieldSelect('panel_scale', _('Panel size'), [
                100 => _('Large'),
                92 => _('Regular'),
                84 => _('Compact')
            ]))->setDefault(92)
        );

        return $this;
    }

    public static function normalizeLayoutValue(int $value, int $min, int $max): int {
        return max($min, min($max, $value));
    }
}