<?php
/**
 * Content types editable from the admin panel.
 * Field types: text, textarea, html, int, bool, slug, icon, image, url, datetime, select, fk
 */
$bi = function (string $name, string $label, string $type = 'text', bool $required = false): array {
    return [
        $name . '_en' => ['label' => $label . ' (English)', 'type' => $type, 'required' => $required],
        $name . '_ar' => ['label' => $label . ' (العربية)', 'type' => $type, 'rtl' => true],
    ];
};
$sort   = ['sort_order' => ['label' => 'Sort order', 'type' => 'int']];
$active = ['is_active' => ['label' => 'Visible on website', 'type' => 'bool']];
$svcFk  = ['service_id' => ['label' => 'Service', 'type' => 'fk', 'ref' => 'services', 'refLabel' => 'title_en', 'required' => true]];

return [
    'services' => [
        'label'  => 'Services',
        'table'  => 'services',
        'list'   => ['title_en', 'slug', 'sort_order', 'is_active'],
        'fields' => ['slug' => ['label' => 'URL slug', 'type' => 'slug', 'required' => true], 'icon' => ['label' => 'Icon', 'type' => 'icon']]
            + $bi('title', 'Title', 'text', true)
            + $bi('hero_title', 'Page heading')
            + $bi('tagline', 'Tagline')
            + $bi('summary', 'Short summary', 'textarea')
            + $bi('intro_title', 'Intro heading')
            + $bi('body', 'Main content', 'html')
            + $bi('why_text', '"Why choose us" text', 'textarea')
            + $bi('cta_title', 'Call-to-action heading')
            + $bi('benefits_title', 'Benefits heading')
            + $bi('benefits_intro', 'Benefits intro', 'textarea')
            + $sort + $active,
    ],
    'service_items' => [
        'label'  => 'Service Sub-items',
        'table'  => 'service_items',
        'list'   => ['service_id', 'title_en', 'sort_order'],
        'fields' => $svcFk + $bi('title', 'Title', 'text', true) + $bi('description', 'Description', 'textarea') + $sort,
    ],
    'service_benefits' => [
        'label'  => 'Service Benefits',
        'table'  => 'service_benefits',
        'list'   => ['service_id', 'title_en', 'sort_order'],
        'fields' => $svcFk + ['icon' => ['label' => 'Icon', 'type' => 'icon']] + $bi('title', 'Title', 'text', true) + $bi('description', 'Description', 'textarea') + $sort,
    ],
    'faqs' => [
        'label'  => 'Service FAQs',
        'table'  => 'faqs',
        'list'   => ['service_id', 'question_en', 'sort_order'],
        'fields' => $svcFk + $bi('question', 'Question', 'text', true) + $bi('answer', 'Answer', 'textarea') + $sort,
    ],
    'features' => [
        'label'  => 'Why Us (Features)',
        'table'  => 'features',
        'list'   => ['label_en', 'title_en', 'sort_order'],
        'fields' => ['icon' => ['label' => 'Icon', 'type' => 'icon']] + $bi('label', 'Label', 'text', true) + $bi('title', 'Title', 'text', true) + $sort,
    ],
    'testimonials' => [
        'label'  => 'Testimonials',
        'table'  => 'testimonials',
        'list'   => ['name_en', 'role_en', 'is_active'],
        'fields' => $bi('name', 'Name', 'text', true) + $bi('role', 'Position / company') + $bi('quote', 'Testimonial', 'textarea') + $sort + $active,
    ],
    'team' => [
        'label'  => 'Leadership Team',
        'table'  => 'team',
        'list'   => ['photo', 'name_en', 'position_en', 'is_active'],
        'fields' => $bi('name', 'Name', 'text', true) + $bi('position', 'Position') + ['photo' => ['label' => 'Photo', 'type' => 'image']] + $sort + $active,
    ],
    'partners' => [
        'label'  => 'Partners',
        'table'  => 'partners',
        'list'   => ['logo', 'name', 'is_active'],
        'fields' => [
            'name' => ['label' => 'Name', 'type' => 'text', 'required' => true],
            'logo' => ['label' => 'Logo', 'type' => 'image'],
            'url'  => ['label' => 'Website URL', 'type' => 'url'],
        ] + $sort + $active,
    ],
    'offices' => [
        'label'  => 'Offices',
        'table'  => 'offices',
        'list'   => ['title_en', 'phone', 'sort_order'],
        'fields' => $bi('title', 'Title', 'text', true) + $bi('address', 'Address', 'textarea') + [
            'phone' => ['label' => 'Phone', 'type' => 'text'],
            'email' => ['label' => 'Email', 'type' => 'text'],
        ] + $sort,
    ],
    'posts' => [
        'label'  => 'Blog & Case Studies',
        'table'  => 'posts',
        'list'   => ['type', 'title_en', 'published_at', 'is_active'],
        'order'  => 'published_at DESC, id DESC',
        'fields' => [
            'type' => ['label' => 'Type', 'type' => 'select', 'options' => ['blog' => 'Blog post', 'case_study' => 'Case study'], 'required' => true],
            'slug' => ['label' => 'URL slug', 'type' => 'slug', 'required' => true],
        ] + $bi('title', 'Title', 'text', true) + $bi('excerpt', 'Excerpt', 'textarea') + $bi('body', 'Content', 'html') + [
            'image'        => ['label' => 'Cover image', 'type' => 'image'],
            'published_at' => ['label' => 'Publish date', 'type' => 'datetime', 'required' => true],
        ] + $active,
    ],
];
