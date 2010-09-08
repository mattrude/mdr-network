<?php
/*
 * Creates simple footnotes using the [ref] shortcode ([ref]My note.[/ref]).
 * This is a import of the simple-footnotes plugin (http://wordpress.org/extend/plugins/simple-footnotes/)
 * Version 0.2 by Andrew Nacin (http://andrewnacin.com/).
 */

class mdr_footnotes {
    var $footnotes = array();
    function mdr_footnotes() {
        add_shortcode( 'ref', array( &$this, 'shortcode' ) );
        add_filter( 'the_content', array( &$this, 'the_content' ), 12 );
    }

    function shortcode( $atts, $content = null ) {
        global $id;
        if ( null === $content )
            return;
        if ( ! isset( $this->footnotes[$id] ) )
            $this->footnotes[$id] = array( 0 => false );
        $this->footnotes[$id][] = $content;
        $note = count( $this->footnotes[$id] ) - 1;
        return ' <a class="simple-footnote" title="' . esc_attr( wp_strip_all_tags( $content ) ) . '" id="return-note-' . $id . '-' . $note . '" href="#note-' . $id . '-' . $note . '"><sup>' . $note . '</sup></a>';
    }

    function the_content( $content ) {
        global $id;
        if ( empty( $this->footnotes[$id] ) )
            return $content;
        $content .= '<div class="simple-footnotes"><h2 class="notes">Footnotes:</h2><ol>';
        foreach ( array_filter( $this->footnotes[$id] ) as $num => $note )
            $content .= '<li id="note-' . $id . '-' . $num . '">' . do_shortcode( $note ) . ' <a href="#return-note-' . $id . '-' . $num . '">&#8617;</a></li>';
        $content .= '</ol></div>';
        return $content;
    }
}
new mdr_footnotes();
