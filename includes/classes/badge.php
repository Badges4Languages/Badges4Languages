<?php

/**
 * 'Badge' class, which contains all the information of the badge.
 * 
 * This badge will be instantiated when a student send a badge to himself or a 
 * teacher send a badge to a student. It will be used to create the JSON file
 * and to send the certification by email.
 * 
 * @author Alexandre LEVACHER
 * @since 1.1.3
*/ 
class Badge {
    
    /*****************************************************
    ********************* ATTRIBUTES *********************
    ******************************************************/
    
    /**
    * Image of the badge.
    * @var String
    */
    private $badge_image;
    
    /**
    * Language of the badge.
    * @var String
    */
    private $badge_lang;
    
    /**
    * Level of the badge (A1 to C2; T1 to T6).
    * @var String
    */
    private $badge_lvl;
    
    /**
    * Name of the badge.
    * @var String
    */
    private $badge_name;
    
    /**
    * Description of the badge.
    * @var String
    */
    private $badge_desc;
    
    /**
    * Type of the badge (Teacher or Student badge).
    * @var String
    */
    private $badge_type;
    
    /**
    * Comment of the badge.
    * @var String
    */
    private $badge_comment;
    
    /**
    * Skills of the badge
    * @var Array
    */
    private $badge_skills;
    
    /**
    * Link of the badge's page
    * @var String
    */
    private $badge_link;
    
    
    
    
    
    /*****************************************************
    ******************** CONSTRUCTORS ********************
    ******************************************************/
    
    /**
    * Get the link of the badge's image
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function __construct($name, $description, $image, $language, $level, $type, $comment, $skills, $link) {
        $this->set_name($name);
        $this->set_description($description);
        $this->set_image($image);
        $this->set_language($language);
        $this->set_level($level);
        $this->set_type($type);
        $this->set_comment($comment);
        $this->set_skills($skills);
        $this->set_link($link);
    }
    
    
    
    
    
    /*****************************************************
    ********************* METHODS *********************
    ******************************************************/
    
    /**
    * Set the link of the badge's image
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_image($imageLink) {
        $this->badge_image = $imageLink;
    }
    
    /**
    * Set the language of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_language($language) {
        $this->badge_lang = $language;
    }
    
    /**
    * Set the level of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_level($level) {
        $this->badge_lvl = $level;
    }
    
    /**
    * Set the name of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_name($name) {
        $this->badge_name = $name;
    }
    
    /**
    * Set the description of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_description($description) {
        $this->badge_desc = $description;
    }
    
    /**
    * Set the type of the badge (Teacher or Student)
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_type($type) {
        $this->badge_type = $type;
    }
    
    /**
    * Set the comment of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_comment($comment) {
        $this->badge_comment = $comment;
    }
    
    /**
    * Set the skills of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_skills($skills) {
        $this->badge_skills = $skills;
    }
    
    /**
    * Set the link of the badge's page
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    */ 
    function set_link($link) {
        $this->badge_link = $link;
    }
    
    /**
    * Get the link of the badge's image
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return String $badge_image Link of the image
    */ 
    function get_image() {
        return $this->badge_image;
    }
    
    /**
    * Get the language of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return String $badge_lang Language of the badge
    */ 
    function get_language() {
        return $this->badge_lang;
    }
    
    /**
    * Get the level of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return String $badge_lvl Level of the badge
    */
    function get_level() {
        return $this->badge_lvl;
    }
    
    /**
    * Get the name of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return String $badge_name Name of the badge
    */
    function get_name() {
        return $this->badge_name;
    }
    
    /**
    * Get the description of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return String $badge_desc Description of the badge
    */
    function get_description() {
        return $this->badge_desc;
    }
    
    /**
    * Get the type of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return String $badge_type Type of the badge
    */
    function get_type() {
        return $this->badge_type;
    }
    
    /**
    * Get the comment of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return String $badge_comment Comment of the badge
    */
    function get_comment() {
        return $this->badge_comment;
    }
    
    /**
    * Get the skills of the badge
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return Array $badge_skills Skills of the badge
    */
    function get_skills() {
        return $this->badge_skills;
    }
    
    /**
    * Get the link of the badge's page
    * 
    * @author Alexandre LEVACHER
    * @since 1.1.3
    * @return Array $badge_link Link of the badge's page
    */
    function get_link() {
        return $this->badge_link;
    }

}
?>