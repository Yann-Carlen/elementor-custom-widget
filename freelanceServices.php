<?php

namespace Elementor;

defined('ABSPATH') || die();

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class FreelanceServices extends Widget_Base
{

    public function __construct($data = array(), $args = null)
    {
        parent::__construct($data, $args);

        wp_register_style('lightslidercss', get_stylesheet_directory_uri() . '/assets/css/lightslider.min.css', array(), '1.0.0', 'all');
        wp_register_script('lightsliderjs', get_stylesheet_directory_uri() . '/assets/js/lightslider.min.js', array('jquery'), '1.0.0', true);
        wp_register_script('lightsliderinit', get_stylesheet_directory_uri() . '/assets/js/lightslider-init.js', array('jquery', 'lightsliderjs'), '1.0.0', true);
    }

    public function get_style_depends()
    {
        $styles = ['lightslidercss'];

        return $styles;
    }

    public function get_script_depends()
    {
        $scripts = ['lightsliderjs', 'lightsliderinit'];

        return $scripts;
    }

    public function get_name()
    {
        return 'freelanceservice';
    }

    public function get_title()
    {
        return 'Freelance Services';
    }

    public function get_icon()
    {
        return 'eicon-favorite';
    }


    public function get_categories()
    {
        return ['basic'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            array(
                'label' => 'Les services',
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => 'Titre',
                'type'    => Controls_Manager::TEXT,
                'default' => 'Titre',
            )
        );

        $this->add_control(
            'text_center',
            array(
                'label'   => "Alignement du text",
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => 'Gauche',
                    'center' => 'Centré',
                    'right' => 'Droite'
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'service_content',
            [
                'label' => "Affichage des services",
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => 'Bordure',
                'selector' => '{{WRAPPER}} .wrapper',
            ]
        );

        $this->add_control(
            'margin',
            [
                'label' => "Marges",
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .margin-services' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'text_service_center',
            array(
                'label'   => "Alignement du text",
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'  => 'Gauche',
                    'center' => 'Centré',
                    'right' => 'Droite'
                ),
            )
        );

        $services = wp_remote_get(getenv('SERVICE_URL'));

        if (!is_wp_error($services)) {
            $services_array = $this->get_services($services);

            $this->add_control(
                'services_array',
                [
                    'label' => "Les services",
                    'type' => \Elementor\Controls_Manager::HIDDEN,
                    'default' => $services_array,
                ]
            );
        }

        $this->add_control(
            'color_alternate',
            [
                'label' => "Alterner les couleurs",
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => "Oui",
                'label_off' => "Non",
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'color_1',
            [
                'label' => "Couleur impaire",
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .odd-bg' => 'background-color: {{VALUE}}',
                ],
            ]
        );


        $this->add_control(
            'color_2',
            [
                'label' => "Couleur paire",
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .even-bg' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'color_3',
            [
                'label' => "Couleur du text",
                'type' => \Elementor\Controls_Manager::COLOR,
                'scheme' => [
                    'type' => \Elementor\Core\Schemes\Color::get_type(),
                    'value' => \Elementor\Core\Schemes\Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .text-color' => 'color: {{VALUE}}',
                ],
            ]
        );


        $this->add_control(
            'margin_services',
            [
                'label' => "Marges entre les services",
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .margin-service' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_services($request)
    {
        return json_decode(wp_remote_retrieve_body($request), true)['services'];
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $services_array = [];

        if (array_key_exists('services_array', $settings)) {
            $services_array = $settings['services_array'];
        }

        $this->add_inline_editing_attributes('title', 'none');
        ?>
        <div style="max-width: 1140px; margin: auto;">
            <h2 style="text-align: <?= esc_attr($settings['text_center']) ?>;" 
            <?= $this->get_render_attribute_string('title'); ?>><?= wp_kses($settings['title'], array()); ?></h2>
        </div>
        <div class="wrapper margin-services">
            <?php if (count($services_array)) { ?>
                <div style="width: 100%; min-height: 350px;">
                    <?php $i = 0;
                    foreach ($services_array as $s) { ?>
                        <?php if ('yes' == $settings['color_alternate']) { ?>
                            <div class="margin-service <?= ($i % 2 == 0) ? 'odd-bg' : 'even-bg' ?>"
                             style="background-color: <?= ($i % 2 == 0) ? $settings['color_1'] : $settings['color_2'] ?>">
                            <?php } else { ?>
                                <div class="margin-service">
                                <?php } ?>
                                <div style="max-width: 1140px; margin: auto;">
                                    <div style="width: 50%; display: inline-block; height: 100%;">
                                        <ul id="light-slider" class="image-gallery" style="width: 100%; text-align:center;">
                                            <?php foreach ($s['images'] as $image) { ?>
                                                <li data-thumb="<?= $image ?>">
                                                    <a href="<?= $image ?>" data-sub-html="#caption2">
                                                    <img src="<?= $image ?>" <?= $s['name'] ?> style="height:300px;" />
                                                </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div style="width: 48%; display: inline-block; height: 100%; vertical-align: top; text-align: 
                                        <?= esc_attr($settings['text_service_center']) ?>;">
                                        <h3 class="text-color" style="color: <?= $settings['color_3'] ?>"><?= $s['name'] ?></h3>
                                        <p class="text-color" style="color: <?= $settings['color_3'] ?>"><?= $s['description'] ?></p>
                                    </div>
                                </div>
                                </div>
                            <?php $i++;
                        } ?>
                            </div>
                        <?php } ?>
                </div>
        <?php
    }

    protected function content_template()
    {
        ?>
        <# view.addInlineEditingAttributes( 'title' , 'none' ); #>
        <div style="max-width: 1140px; margin: auto;">
            <h2 style="text-align: {{ settings.text_center }};" {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</h2>
        </div>
        <div class="wrapper margin-services" id="freelance-services">
            <# if (settings.services_array) { #>
                <div style="width: 100%; min-height: 350px;">
                    <#
                    let i = 0;
                    #>
                    <# _.each(settings.services_array, function(s) { #>
                        <# if ('yes' == settings.color_alternate) { #>
                            <# if (i % 2 == 0) { #>
                                <div class="margin-service odd-bg" style="background-color: {{ settings.color_1 }};">
                            <# } else { #>
                                <div class="margin-service even-bg" style="background-color: {{ settings.color_2 }};">
                            <# } #>
                        <# } else { #>
                            <div class="margin-service">
                        <# } #>
                        <div style="max-width: 1140px; margin: auto;">
                            <div style="width: 50%; display: inline-block; height: 100%;">
                                <ul id="light-slider" class="image-gallery" style="width: 100%; text-align:center;">
                                    <# _.each(s.images, function(image) { #>
                                        <li data-thumb="{{ image }}">
                                            <a href="{{ image }}" data-sub-html="#caption2"> <img src="{{ image }}" alt="{{ s.name }}" /></a>
                                        </li>
                                        <# }); #>
                                </ul>
                            </div>
                            <div style="width: 48%; display: inline-block; height: 100%; vertical-align: top; text-align: {{ settings.text_service_center }};">
                                <h3 class="text-color" style="color: {{ settings.color_3 }};">{{{ s.name }}}</h3>
                                <p class="text-color" style="color: {{ settings.color_3 }};">{{{ s.description }}}</p>
                            </div>
                        </div>
                    </div>
                    <# ++i; #>
                    <# }); #>
            </div>
            <# } #>
        </div>
        <?php
    }  
}