<?php
/**
 * Template Name: Social Contract
 *
 * @package Loomaama
 */

get_header();

?>
<main>
<!-- HERO SECTION -->
<?php 
$hero_image = get_field('social_hero_image');
$hero_video = get_field('social_hero_video');

// Handle different ACF field return formats
if (is_array($hero_video)) {
    $hero_video = !empty($hero_video['url']) ? $hero_video['url'] : (!empty($hero_video[0]) ? $hero_video[0] : '');
} elseif (is_object($hero_video) && isset($hero_video->url)) {
    $hero_video = $hero_video->url;
}

$hero_video = esc_url($hero_video);
$has_video = !empty($hero_video);
?>

<section class="hero-editorial jarallax" >
    <?php if (!$has_video && $hero_image): ?>
        <div class="jarallax-img" style="background-image: url('<?php echo esc_url($hero_image); ?>');"></div>
    <?php endif; ?>
    
    <div class="hero-editorial-content reveal" data-aos="fade">
        <span class="eyebrow mb-4 d-block" data-aos="fade-up" data-aos-delay="100">
            <?php echo esc_html(get_field('social_hero_eyebrow') ?: ''); ?>
        </span>
        <h1 class="headline display-3 mb-4 fw-bold" data-aos="fade-up" data-aos-delay="200">
            <?php echo esc_html(get_field('social_hero_title') ?: 'We share the profit because<br>we share the planet.'); ?>
        </h1>
        <p class="fs-5 mx-auto mt-4" style="max-width: 800px; font-weight: 300; line-height: 1.8;" data-aos="fade-up" data-aos-delay="300">
            <?php echo esc_html(get_field('social_hero_subtitle') ?: 'Every LOOMAAMA purchase is distributed transparently. Here\'s exactly how.'); ?>
        </p>
    </div>
</section>

    <!-- SECTION 1: THE BREAKDOWN -->
	<?php /*
    <section class=" mt-md-5">
        <div class="container ">
            <div class="row align-items-center mb-5 reveal" data-aos="fade">
                <div class="col-lg-5 mb-lg-0">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('breakdown_eyebrow') ?: ''); ?></span>
                    <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('breakdown_title') ?: 'The Full Picture.'); ?></h2>
                </div>
                <div class="col-lg-6 offset-lg-1" data-aos="fade-up" data-aos-delay="300">
                    <p class="text-secondary fs-5 lh-lg mb-0">
                        <?php echo esc_html(get_field('breakdown_text') ?: 'When you purchase a LOOMAAMA garment, here\'s where that money goes. We built our pricing around one belief. Value should return to where it began. Here is exactly how that works.'); ?>
                    </p>
                </div>
            </div>

            <!-- Custom Pricing Infographic -->
            <div class="row reveal">
                <div class="col-12">
                    <?php 
                    $artisan_percent = get_field('artisan_percentage') ?: 60;
                    $ops_percent = get_field('ops_percentage') ?: 25;
                    $growth_percent = get_field('growth_percentage') ?: 15;
                    $total_price = 200;
                    $artisan_amount = ($artisan_percent / 100) * $total_price;
                    $ops_amount = ($ops_percent / 100) * $total_price;
                    $growth_amount = ($growth_percent / 100) * $total_price;
                    ?>
                    <div class="pricing-infographic">
                        <!-- Bar Graph -->
                        <div class="stacked-bar-container" data-aos="fade-up" data-aos-delay="100">
                            <div class="stacked-segment seg-artisan " style="width: <?php echo $artisan_percent; ?>%;"><div class="counter" data-start="0" data-end="<?php echo $artisan_percent; ?>" data-animation="ease-in"></div>%</div>
                            <div class="stacked-segment seg-ops " style="width: <?php echo $ops_percent; ?>%;"><div class="counter" data-start="0" data-end="<?php echo $ops_percent; ?>" data-animation="ease-in"></div>%</div>
                            <div class="stacked-segment seg-growth " style="width: <?php echo $growth_percent; ?>%;"><div class="counter" data-start="0" data-end="<?php echo $growth_percent; ?>" data-animation="ease-in"></div>%</div>
                        </div>

                        <!-- Legend / Detailed Breakdown -->
                        <div class="breakdown-legend" data-aos="fade-up" data-aos-delay="200">
                            <!-- Group 1: Artisan -->
                            <div class="legend-item artisan">
                                <div class="d-flex justify-content-between align-items-center mb-2" data-aos="fade-up" data-aos-delay="300">
                                  <h5 class="headline mb-0 fs-6 fs-md-5" style="color: var(--chart-artisan);"><?php echo esc_html(get_field('artisan_breakdown_title') ?: 'Artisan Wages + Materials'); ?></h5>
                                    <span class="headline fs-4 fw-bold">$<?php echo number_format($artisan_amount, 2); ?></span>
                                </div>
                                <ul class="sub-breakdown">
                                    <?php 
                                    $artisan_breakdown = get_field('artisan_breakdown');
                                    if ($artisan_breakdown):
                                        foreach ($artisan_breakdown as $key => $item):
                                            $delay = 100 + ($key * 100);
                                    ?>
                                    <li data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>"><span><?php echo esc_html($item['item']); ?></span> <span class="counter" data-start="0" data-end="<?php echo $item['amount']; ?>" data-animation="ease-in"></span></li>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                    <li><span>Artisan labor</span> <span>$<?php echo round($artisan_amount * 0.67); ?></span></li>
                                    <li><span>Fiber sourcing & processing</span> <span>$<?php echo round($artisan_amount * 0.33); ?></span></li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <!-- Group 2: Operations -->
                            <div class="legend-item ops">
                                <div class="d-flex justify-content-between align-items-center mb-2" data-aos="fade-up" data-aos-delay="400">
                                   <h5 class="headline mb-0 fs-6 fs-md-5" style="color: var(--chart-ops);"><?php echo esc_html(get_field('ops_breakdown_title') ?: 'Operations & Logistics'); ?></h5>
                                    <span class="headline fs-4 fw-bold">$<?php echo number_format($ops_amount, 2); ?></span>
                                </div>
                                <ul class="sub-breakdown">
                                    <?php 
                                    $ops_breakdown = get_field('ops_breakdown');
                                    if ($ops_breakdown):
                                        foreach ($ops_breakdown as $key => $item):
                                            $delay = 100 + ($key * 100);
                                    ?>
                                    <li data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>"><span><?php echo esc_html($item['item']); ?></span> <span class="counter" data-start="0" data-end="<?php echo $item['amount']; ?>" data-animation="ease-in"></span></li>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                    <li><span>Shipping & packaging</span> <span>$<?php echo number_format($ops_amount * 0.4, 2); ?></span></li>
                                    <li><span>Website & payment proc.</span> <span>$<?php echo number_format($ops_amount * 0.3, 2); ?></span></li>
                                    <li><span>Quality control & admin</span> <span>$<?php echo number_format($ops_amount * 0.2, 2); ?></span></li>
                                    <li><span>Customer service</span> <span>$<?php echo number_format($ops_amount * 0.1, 2); ?></span></li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <!-- Group 3: Growth -->
                            <div class="legend-item growth">
                                <div class="d-flex justify-content-between align-items-center mb-2" data-aos="fade-up" data-aos-delay="500">
                                    <h5 class="headline mb-0 fs-6 fs-md-5" style="color: var(--chart-growth);"><?php echo esc_html(get_field('growth_breakdown_title') ?: 'LOOMAAMA Growth'); ?></h5>
                                    <span class="headline fs-4 fw-bold">$<?php echo number_format($growth_amount, 2); ?></span>
                                </div>
                                <ul class="sub-breakdown">
                                    <?php 
                                    $growth_breakdown = get_field('growth_breakdown');
                                    if ($growth_breakdown):
                                        foreach ($growth_breakdown as $key => $item):
                                            $delay = 100 + ($key * 100);
                                    ?>
                                    <li data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>"><span><?php echo esc_html($item['item']); ?></span> <span class="counter" data-start="0" data-end="<?php echo $item['amount']; ?>" data-animation="ease-in"></span></li>
                                    <?php 
                                        endforeach;
                                    else:
                                    ?>
                                    <li><span>New collection dev.</span> <span>$<?php echo number_format($growth_amount * 0.5, 2); ?></span></li>
                                    <li><span>Marketing & education</span> <span>$<?php echo number_format($growth_amount * 0.33, 2); ?></span></li>
                                    <li><span>Emergency fund</span> <span>$<?php echo number_format($growth_amount * 0.17, 2); ?></span></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: ARTISAN WAGES -->
    <section class="" style="background: var(--bg-card);">
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0 reveal" data-aos="fade-up">
                    <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('wages_eyebrow') ?: ''); ?></span>
                    <h2 class="headline display-6 mb-4"  data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('wages_title') ?: 'What Artisans Actually Earn'); ?></h2>
                    <p class="text-secondary lh-lg mb-4" data-aos="fade-up" data-aos-delay="200">
                        <?php echo esc_html(get_field('wages_intro') ?: 'LOOMAAMA artisans earn an average of'); ?> 
                        <strong class="text-dark"><?php echo get_field('wage_multiplier') ?: '3'; ?><?php echo esc_html(get_field('wage_multiplier_suffix') ?: 'x the local minimum wage in Nepal.'); ?></strong>
                    </p>
                   <p class="text-secondary lh-lg mb-0" data-aos="fade-up" data-aos-delay="300">
                        <?php echo esc_html(get_field('wages_description') ?: 'Artisans are paid per piece, not per hour, allowing them to work at their own pace and set their own schedules. Payment is made directly upon completion; no delays, no middlemen.'); ?>
                    </p>
                </div>
                
                <div class="col-lg-6 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="400">
                    <div class="luxury-table-wrapper">
                        <table class="luxury-table">
                            <thead data-aos="fade-up" data-aos-delay="500">
                                <tr>
                                    <th>Role</th>
                                    <th>LOOMAAMA Wage</th>
                                    <th>Local Min. Wage</th>
                                    <th>Multiplier</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $wage_table = get_field('wage_table');
                                if ($wage_table):
                                    foreach ($wage_table as $key => $row):
                                        $delay = 500 + ($key * 100);
                                ?>
                                <tr>
                                    <td data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>"><?php echo esc_html($row['role']); ?></td>
                                    <td data-aos="fade-up" data-aos-delay="<?php echo $delay + 100; ?>"><?php echo esc_html($row['loomaama_wage']); ?></td>
                                    <td data-aos="fade-up" data-aos-delay="<?php echo $delay + 200; ?>"><?php echo esc_html($row['minimum_wage']); ?></td>
                                    <td data-aos="fade-up" data-aos-delay="<?php echo $delay + 300; ?>"><?php echo esc_html($row['multiplier_value']); ?></td>
                                </tr>
                                <?php 
                                    endforeach;
                                else:
                                ?>
                               
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

 <!-- SECTION 3: MATERIAL COSTS -->
<section class=" my-md-5">
    <div class="container ">
        <div class="row align-items-center flex-lg-row-reverse">
            <div class="col-lg-5 mb-5 mb-lg-0 reveal" data-aos="fade-up" >
                <span class="eyebrow text-secondary mb-3 d-block" data-aos="fade-up" data-aos-delay="500">
                    <?php echo esc_html(get_field('materials_eyebrow') ?: ''); ?>
                </span>
                <h2 class="headline display-6 mb-4"  data-aos="fade-up" data-aos-delay="500">
                    <?php echo esc_html(get_field('materials_title') ?: 'The True Cost of Natural Fiber'); ?>
                </h2>
                <p class="text-secondary lh-lg mb-4" data-aos="fade-up" data-aos-delay="600">
                    <?php echo esc_html(get_field('materials_text') ?: 'Wild nettle fiber costs 5-10x more than conventional cotton. Hemp costs 3-5x more. Why?'); ?>
                </p>
                <p class="text-secondary lh-lg mb-4" data-aos="fade-up" data-aos-delay="700">
                    <?php echo esc_html(get_field('materials_text_2') ?: 'Because these fibers are harvested sustainably, processed by hand, and sourced from small-scale producers who are paid fairly.'); ?>
                </p>
                <p class="text-main lh-lg fw-medium mb-0" data-aos="fade-up" data-aos-delay="800">
                    <?php echo esc_html(get_field('materials_text_3') ?: 'We don\'t cut corners on materials. The fiber is the foundation of quality.'); ?>
                </p>
            </div>
            <div class="col-lg-5 offset-lg-1 reveal" data-aos="fade-up" data-aos-delay="900">
                <div class="stat-box">
                    <i class="fa-solid fa-seedling mb-4 fs-2" style="color: var(--wild-nettle);"></i>
                    <h5 class="headline text-uppercase letter-spacing-1 mb-3" data-aos="fade-up" data-aos-delay="900">
                        <?php echo esc_html(get_field('material_stat_title') ?: 'Material Cost Reality'); ?>
                    </h5>
                    <p class="headline fs-4 fw-bold mb-3" data-aos="fade-up" data-aos-delay="1000">
                        <?php echo esc_html(get_field('material_stat_value') ?: '1kg of wild nettle fiber: $45'); ?>
                    </p>
                    <p class="text-secondary mb-0" data-aos="fade-up" data-aos-delay="1100">
                        <?php echo esc_html(get_field('material_stat_comparison') ?: '(vs. $5 for conventional cotton)'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
*/?>
  <!-- SECTION 4: OPERATIONS & LOGISTICS -->
<section class="" style="background: var(--bg-card); " data-aos="fade-up">
    <div class="container ">
        <div class="row text-center mb-5 reveal">
            <div class="col-lg-8 mx-auto">
                <h2 class="headline display-6 mb-4 " data-aos="fade-up" data-aos-delay="200">
                    <?php echo esc_html(get_field('operations_title') ?: 'The Hidden Costs of Ethical Production'); ?>
                </h2>
                <p class="fs-5 lh-lg" data-aos="fade-up" data-aos-delay="300">
                    <?php echo esc_html(get_field('operations_description') ?: 'Ethical production is not cheap. It was never meant to be. Here is exactly what goes into running a brand that refuses to cut corners where it matters most.'); ?>
                </p>
            </div>
        </div>

        <div class="row ops-grid-wrapper reveal" data-aos="fade-up">
            <div class="col-12">
                <div class="ops-grid">
                    <?php 
                    $ops_cards = get_field('operations_cards');
                    if ($ops_cards):
                        foreach ($ops_cards as $key => $card):
                    ?>
                    <div class="ops-card" data-aos="fade-up" data-aos-delay="<?php echo 100 + ($key * 100); ?>">
                        <div class="ops-icon"><i class="<?php echo esc_attr($card['icon']); ?>"></i></div>
                        <h5 class="headline mb-3 text-dark"><?php echo esc_html($card['title']); ?></h5>
                        <p class="text-secondary mb-0"><?php echo esc_html($card['text']); ?></p>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <!-- Default cards -->
                    <div class="ops-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="ops-icon"><i class="fa-solid fa-box-open"></i></div>
                        <h5 class="headline mb-3 text-dark">Shipping</h5>
                        <p class="text-secondary mb-0">We use plastic-free packaging and carbon-neutral shipping.</p>
                    </div>
                    <div class="ops-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="ops-icon"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
                        <h5 class="headline mb-3 text-dark">Quality Control</h5>
                        <p class="text-secondary mb-0">Every garment is inspected twice before shipping.</p>
                    </div>
                    <div class="ops-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="ops-icon"><i class="fa-solid fa-credit-card"></i></div>
                        <h5 class="headline mb-3 text-dark">Payment Processing</h5>
                        <p class="text-secondary mb-0">We absorb credit card fees so artisans receive full payment.</p>
                    </div>
                    <div class="ops-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="ops-icon"><i class="fa-solid fa-headset"></i></div>
                        <h5 class="headline mb-3 text-dark">Customer Service</h5>
                        <p class="text-secondary mb-0">We answer every question, handle every return with care.</p>
                    </div>
                    <div class="ops-card" data-aos="fade-up" data-aos-delay="500">
                        <div class="ops-icon"><i class="fa-solid fa-laptop-code"></i></div>
                        <h5 class="headline mb-3 text-dark">Website & Tech</h5>
                        <p class="text-secondary mb-0">Maintaining a transparent, user-friendly platform.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="row mt-5 text-center reveal" data-aos="fade-up" data-aos-delay="600">
            <div class="col-12">
                <p class="headline fs-5 fw-bold" style="letter-spacing: 0.1em;"><?php echo esc_html(get_field('operations_footer_text') ?: 'These costs are real, and we don\'t hide them.'); ?></p>
            </div>
        </div>
    </div>
</section>

    <!-- SECTION 5: SALES & EMERGENCY FUND -->
<section class=" my-md-5" data-aos="fade-up">
    <div class="container ">
        <div class="row">
            <!-- Why we don't do sales -->
            <div class="col-lg-6 reveal" data-aos="fade-up" data-aos-delay="100">
                <div class="p-5 h-100" style="background: var(--bg-editorial);">
                    <span class="eyebrow text-secondary mb-3 d-block"><?php echo esc_html(get_field('no_sales_eyebrow') ?: ''); ?></span>
                    <h2 class="headline mb-4 fs-3"><?php echo wp_kses_post(get_field('no_sales_title') ?: 'No Discounts.<br>No Sales. Here\'s Why.'); ?></h2>
                    <p class="text-secondary lh-lg mb-0 fs-5">
                        <?php echo esc_html(get_field('no_sales_text') ?: 'We never do "Black Friday" or "Clearance." Instead, we do "Artisan Days," where 100% of the profit for a day goes to a specific village project.'); ?>
                    </p>
                </div>
            </div>
            
            <!-- The Emergency Fund -->
            <div class="col-lg-6 reveal" data-aos="fade-up" data-aos-delay="200">
                <div class="p-5 h-100 border" style="border-color: var(--border) !important;">
                    <span class="eyebrow text-secondary mb-3 d-block"><?php echo esc_html(get_field('emergency_fund_eyebrow') ?: ''); ?></span>
                    <h2 class="headline mb-4 fs-3"><?php echo esc_html(get_field('emergency_fund_title') ?: 'The Emergency Fund'); ?></h2>
                    <div class="text-secondary lh-lg mb-4">
                        <?php echo wp_kses_post(get_field('emergency_fund_text') ?: '<p>5% of every purchase goes into an emergency fund for artisans. This fund provides:</p><ul><li>Medical expenses for artisan families</li><li>Support during natural disasters or crises</li><li>Interest-free loans for equipment or training</li><li>Childcare assistance</li></ul><p class="text-main fw-bold mb-0">This isn\'t charity—it\'s community care.</p>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 6: WHAT WE DON'T DO -->
<section class="" style="background: var(--bg-card); border-top: 1px solid var(--border);" data-aos="fade-up">
    <div class="container ">
        <div class="row text-center mb-5 reveal">
            <div class="col-12">
                <h2 class="headline display-6" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('non_negotiables_title') ?: 'Our Non-Negotiables'); ?></h2>
                <p class="text-secondary fs-5 mt-3" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('non_negotiables_subtitle') ?: 'LOOMAAMA will never:'); ?></p>
            </div>
        </div>
        
        <div class="row justify-content-center reveal" data-aos="fade-up" data-aos-delay="300">
            <div class="col-lg-8">
                <ul class="non-negotiables-list">
                    <?php 
                    $non_negotiables = get_field('non_negotiables');
                    if ($non_negotiables):
                        foreach ($non_negotiables as $key => $item):
                    ?>
                    <li data-aos="fade-up" data-aos-delay="<?php echo esc_attr($key * 100); ?>"><i class="fa-solid fa-xmark"></i> <?php echo esc_html($item['item']); ?></li>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <li><i class="fa-solid fa-xmark"></i> Pay below fair wages to reduce costs</li>
                    <li><i class="fa-solid fa-xmark"></i> Use synthetic materials to increase margins</li>
                    <li><i class="fa-solid fa-xmark"></i> Outsource to factories with poor conditions</li>
                    <li><i class="fa-solid fa-xmark"></i> Mark up prices to accommodate sales</li>
                    <li><i class="fa-solid fa-xmark"></i> Hide costs or inflate impact claims</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
 <!-- SECTION 7: THIRD-PARTY VERIFICATION -->
<section class=" my-md-5" data-aos="fade-up" data-aos="fade-up">
    <div class="container  text-center reveal" >
        <div class="verification-badge border-1" data-aos="fade-up" data-aos-delay="100">
            <i class="fa-solid fa-check"></i>
        </div>
        <h2 class="headline display-6 mb-4" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html(get_field('verification_title') ?: 'Accountability'); ?></h2>
        <p class="text-secondary fs-5 mx-auto mb-5 lh-lg" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="300">
            <?php echo esc_html(get_field('verification_text') ?: 'LOOMAAMA\'s wage and impact claims are verified by third-party organizations. We undergo annual audits to ensure compliance with fair trade standards.'); ?>
        </p>
        <?php $cert_link = get_field('certification_link'); ?>
        <?php if ($cert_link): ?>
        <a data-aos="fade-up" data-aos-delay="300" href="<?php echo esc_url($cert_link); ?>" class="btn btn-outline-dark px-4 py-3 btn-lux headline"><?php echo esc_html(get_field('certification_button_text') ?: 'View Our Certification'); ?></a>
        <?php endif; ?>
    </div>
</section>

<!-- CLOSING CTA -->
<section class=" text-center" style="background: var(--bg-card);" data-aos="fade-up">
    <div class="container  reveal">
        <h2 class="headline display-5 mb-4" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html(get_field('closing_title') ?: 'This is How Fashion Should Work.'); ?></h2>
        <p class="text-secondary fs-5 mx-auto mb-5 lh-lg" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="200">
            <?php echo esc_html(get_field('closing_text') ?: 'Transparent pricing. Fair wages. No hidden costs. No compromises.'); ?>
        </p>
        <a data-aos="fade-up" data-aos-delay="300" href="<?php echo esc_url(get_field('closing_button_link') ?: wc_get_page_permalink('shop')); ?>" class=" cta-buttonbtn btn btn-dark px-5 py-3 btn-lux headline fs-6"><?php echo esc_html(get_field('closing_button_text') ?: 'Shop with Confidence'); ?></a>
    </div>
</section>

</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure jarallax and video plugin are loaded
    if (typeof jarallax !== 'undefined') {
        // Re-initialize jarallax with video settings
        var elements = document.querySelectorAll('.jarallax');
        
        elements.forEach(function(element) {
            // Destroy existing instance if any
            if (element.jarallax) {
                element.jarallax.destroy();
            }
            
            // Initialize with video settings
            jarallax(element, {
                speed: 0.5,
                videoVolume: 0,
                videoLoop: true,
                videoStartTime: 0,
                videoEndTime: 0,
                imgPosition: '50% 50%',
                imgSize: 'cover',
                imgRepeat: 'no-repeat',              
                videoSrc: `mp4:<?php echo $hero_video; ?>`

            });
        });
    }
    
    // Fallback: Force video to play after a delay
    setTimeout(function() {
        var videoSection = document.querySelector('.jarallax[data-jarallax-video]');
        if (videoSection) {
            var video = videoSection.querySelector('video');
            if (video) {
                video.muted = true;
                video.play().catch(function(e) {
                    console.log('Video play error:', e);
                });
            }
        }
    }, 500);
});
</script>
<?php get_footer(); ?>