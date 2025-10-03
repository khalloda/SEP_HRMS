import './bootstrap';

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';

const uuidFallback = () => {
    if (window.crypto && typeof window.crypto.randomUUID === 'function') {
        return window.crypto.randomUUID();
    }

    return `uuid-${Math.random().toString(36).slice(2, 11)}`;
};

const normaliseGroups = (componentGroups) => {
    const parsed = typeof componentGroups === 'string' ? JSON.parse(componentGroups) : componentGroups;

    return Object.entries(parsed || {}).map(([type, items]) => ({
        type,
        label: type.charAt(0).toUpperCase() + type.slice(1),
        items: items.map(item => ({
            id: item.id,
            label: `${item.code} · ${item.name}`,
        })),
    }));
};

const normaliseRows = (seededComponents) => {
    const parsed = typeof seededComponents === 'string' ? JSON.parse(seededComponents) : seededComponents;

    const rows = Object.values(parsed || {}).map(component => ({
        uuid: uuidFallback(),
        component: component.component_id ?? '',
        amount: component.value_numeric ?? '',
        formula: component.formula_expr ?? '',
        priority: component.priority_order ?? '',
    }));

    if (rows.length === 0) {
        rows.push({
            uuid: uuidFallback(),
            component: '',
            amount: '',
            formula: '',
            priority: '',
        });
    }

    rows.forEach((row, index) => {
        row.priority = index + 1;
    });

    return rows;
};

const submitThroughRepeater = (event) => {
    const repeaterEl = event.target.querySelector('[data-salary-structure-repeater]');
    const repeaterData = repeaterEl?.__x?.$data;

    if (repeaterData?.handleSubmit) {
        repeaterData.handleSubmit(event);
        return;
    }

    event.target.submit();
};

Alpine.data('salaryStructureForm', () => ({
    handleSubmit(event) {
        event.preventDefault();
        submitThroughRepeater(event);
    },
}));

Alpine.data('componentRepeater', (componentGroups, seededComponents) => ({
    componentGroups: normaliseGroups(componentGroups),
    rows: normaliseRows(seededComponents),
    init() {
        this.$nextTick(() => {
            if (this.$refs.sortable && window.Sortable) {
                window.Sortable.create(this.$refs.sortable, {
                    handle: '.drag-handle',
                    animation: 150,
                    onUpdate: () => this.reorder(),
                });
            }
        });
    },
    reorder() {
        this.rows.forEach((row, index) => {
            row.priority = index + 1;
        });
    },
    addRow() {
        this.rows.push({
            uuid: uuidFallback(),
            component: '',
            amount: '',
            formula: '',
            priority: '',
        });
        this.reorder();
    },
    removeRow(index) {
        this.rows.splice(index, 1);
        if (this.rows.length === 0) {
            this.addRow();
        } else {
            this.reorder();
        }
    },
    handleSubmit(event) {
        this.reorder();
        if (this.rows.every(row => !row.component)) {
            event.preventDefault();
            this.addRow();
            return;
        }

        event.target.submit();
    },
}));

window.Alpine = Alpine;
window.Sortable = Sortable;

Alpine.start();
