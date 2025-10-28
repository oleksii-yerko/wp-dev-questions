<?php
$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

$args = array(
    'post_type'           => 'project',
    'post_status'         => 'publish',
    'posts_per_page'      => 10,                 
    'paged'               => $paged,             
    'ignore_sticky_posts' => true,                    
    'orderby'             => array(
        'meta_value_num' => 'DESC',              
        'date'           => 'DESC',             
    ),
    'meta_key'            => 'rating',          
    'tax_query'           => array(      
        'relation' => 'AND',
        array(
            'taxonomy' => 'project_category',
            'field'    => 'slug',
            'terms'    => array( 'web', 'mobile' ),
            'operator' => 'IN',
        ),
    ),
    'meta_query' => array(                   
        'relation' => 'AND',
        array(
            'key'     => 'cost',
            'value'   => array( 1000, 10000 ),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        ),
        array(
            'key'     => 'is_featured',
            'value'   => '1',
            'compare' => '=',
        ),
    ),
    'date_query' => array(                   
        array(
            'after'     => '1 year ago',
            'inclusive' => true,
        ),
    ),
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) :

    echo '<div class="projects-list">';

    while ( $query->have_posts() ) : $query->the_post();
      
        $rating = get_post_meta( get_the_ID(), 'rating', true );
        $cost   = get_post_meta( get_the_ID(), 'cost', true );

        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('project-item'); ?>>
            <h2 class="project-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="project-meta">
                <span class="project-rating">Rating: <?php echo esc_html( $rating ?: '—' ); ?></span>
                <span class="project-cost">Cost: <?php echo esc_html( $cost ? number_format_i18n( $cost ) . ' UAH' : 'N/A' ); ?></span>
                <span class="project-date"><?php echo get_the_date(); ?></span>
            </div>
            <div class="project-excerpt"><?php the_excerpt(); ?></div>
            <div class="project-terms"><?php the_terms( get_the_ID(), 'project_category', '', ', ' ); ?></div>
        </article>
        <?php

    endwhile;

    echo '</div>';

    $big = 999999999;

    $pagination = paginate_links( array(
        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, $paged ),
        'total'     => $query->max_num_pages,
        'type'      => 'list',
        'prev_text' => '&laquo; Назад',
        'next_text' => 'Вперед &raquo;',
    ) );

    if ( $pagination ) {
        echo '<nav class="projects-pagination" aria-label="Projects pagination">' . $pagination . '</nav>';
    }

else :
    echo '<p>Поки що немає проєктів, які відповідають критеріям.</p>';
endif;

wp_reset_postdata();
?>
