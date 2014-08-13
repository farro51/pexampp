<?php
/**
 *  This file is part of Restos software
 * 
 *  Restos is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 * 
 *  Restos is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 * 
 *  You should have received a copy of the GNU General Public License
 *  along with Restos.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * 
 * Class used to represent a zone (dimension, area, instance or some similar concept)
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DPlatform extends SpecificationEntity {
    
    /**
     * 
     * Virtual world name
     *
     * @var string
     */
    public $name;
    
    /**
     * 
     * URL of service to "open the door" to users in the virtual world
     * @var string
     */
    public $doorService;
    
    /**
     * 
     * List of zones in virtual world
     * @var array
     */
    public $zones = array();
    
    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
        $this->Resources = array('doorService');
 
    }    
}

/**
 * 
 * Class used to represent a zone (dimension, area, instance or some similar concept)
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DZone extends SpecificationEntity {
    
    /**
     * 
     * Zone name
     *
     * @var string
     */
    public $name;
    
    /**
     * URI of an thumbnail image about the zone, if is posible 
     * 
     * @var string
     */
    public $thumbnail;
        
    /**
     * List of messengers enabled in the zone  
     * @var array of VW3DMessenger
     */
    public $messenger;
    
    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
 
    }
}

/**
 * 
 * Class used to represent a messenger, regardless of the type
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DMessenger extends SpecificationEntity {
    
    /**
     * 
     * Name of messager
     *
     * @var string
     */
    public $name;
    
    /**
     * 
     * Messager description
     *
     * @var string
     */
    public $description;
    
    /**
     * 
     * Type of message
     *
     * @var string something: visit, interaction, feedback or question
     */
    public $type;

    /**
     * 
     * true if the messenger is active; false in other case
     * @var bool
     */
    public $actived;
    
    /**
     * 
     * The URI to send or request the information
     * @var string
     */
    public $reportTo;
        
    /**
     * 
     * The activity identify, when the messenger is linked with an activity
     * @var string
     */
    public $activity;

    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
        $this->Resources = array('responseTo');
 
    }
}

/**
 * 
 * Class used to represent a message, regardless of the type
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DMessage extends SpecificationEntity {

    /**
     * Who and Where 
     * 
     * @var VW3DIdentity
     */
    public $identity;
    
    
    /**
     * 
     * Type of message
     *
     * @var string something: visit, interaction, feedback, question or tracking
     */
    public $type;

    /**
     * Information in the message. The content type depend of the message type  
     * @var object
     */
    public $content;

    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
    }
}

/**
 * 
 * Class used to identify the virtual world and the user
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DIdentity extends SpecificationEntity {
    /**
     * User identify 
     * 
     * @var string
     */
    public $uid;
    
    /**
     * 
     * The messenger in the communication
     * @var string
     */
    public $messenger;
        
    /**
     * A direction in somewhere
     *   
     * @var VW3DLocalization
     */
    public $localization;

    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
        $this->Resources = array('messenger', 'uid');
 
    }
}

/**
 * 
 * Class used to define a location in a virtual world
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DLocalization extends SpecificationEntity {
    /**
     * Virtual world identify 
     * 
     * @var string
     */
    public $world;
        
    /**
     * A zone identify. Zone is a dimension, area, instance or some similar concept
     *   
     * @var string
     */
    public $zone;

    /**
     * A float triplet of x, y and z coordinates, each number is separate by space
     *
     * @example "22 4.5 349" 
     * @var string
     */
    public $position;

    /**
     * A float quaternion of x, y, z and w coordinates, each number is separate by space
     * w component is the angle
     *
     * @example "22 4.5 349 10"
     * @var string
     */
    public $rotation;

    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
        $this->Resources = array('world', 'zone');
 
    }
}

/**
 * 
 * Class used to represent a Interaction log in custom format
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DInteractionLog extends SpecificationEntity {
    /**
     * When the log occurs 
     * 
     * @var unixtime
     */
    public $time;
        
    /**
     * An action (touch, write, take, select... and others)  
     * @var string
     */
    public $action;
    
    /**
     * 
     * Information about the action
     * @var string
     */
    public $information;
    
    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

	    // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
 
    }
}

/**
 * 
 * Class used to represent a Interaction log in custom format
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DVisitLog extends VW3DInteractionLog {
    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        $this->action = 'visit';

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
 
    }
}

/**
 * 
 * Class used to represent a feedback in custom format
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DFeedback extends SpecificationEntity {
    /**
     * The mime type of the document referenced in the URL property
     * Official MIME info in:
     * - RFC-822   Standard for ARPA Internet text messages - http://www.rfc-editor.org/rfc/rfc822.txt
     * - RFC-2045 MIME Part 1: Format of Internet Message Bodies - http://www.rfc-editor.org/rfc/rfc2045.txt
     * - RFC-2046 MIME Part 2: Media Types - http://www.rfc-editor.org/rfc/rfc2046.txt
     * - RFC-2047 MIME Part 3: Header Extensions for Non-ASCII Text - http://www.rfc-editor.org/rfc/rfc2047.txt
     * - RFC-2048 MIME Part 4: Registration Procedures - http://www.rfc-editor.org/rfc/rfc2048.txt
     * - RFC-2049 MIME Part 5: Conformance Criteria and Examples - http://www.rfc-editor.org/rfc/rfc2049.txt
     * 
     * List of mime types in: http://www.w3schools.com/media/media_mimeref.asp
     * 
     * @var string
     * @example image/png
     */
    public $mime;
        
    /**
     * Document URI  
     * @var string
     */
    public $uri;
    
    /**
     * Options to display the document
     * The value is represented in URL format 
     * 
     * @var string
     * @example fullscreen=yes&autoplay=false
     */
    public $displayoptions;
    
    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
        $this->Resources = array('uri');
 
    }
}

/**
 * 
 * Class used to represent a question in QTI format
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DQuestion extends SpecificationEntity {
        
    /**
     * List of IMS questions   
     * @var IMSQuestionary
     */
    public $imsquestions;
    
    
    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
 
    }
}


/**
 * 
 * Class used to represent a Event tracking
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DTracking extends SpecificationEntity {
    /**
     * When the event occurs 
     * 
     * @var unixtime
     */
    public $time;
        
    /**
     * A key  
     * @var string
     */
    public $element;
    
    /**
     * 
     * Information about the element key
     * @var string
     */
    public $value;
    
    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
 
    }
}

/**
 * 
 * Class used to represent an Activity in the virtual world
 * 
 * @author David Herney <davidherney@gmail.com>
 * @package Laberinto.VW3D
 * @version 0.1
 *
 */
class VW3DActivity extends SpecificationEntity {
    
    /**
     * 
     * Activity name
     *
     * @var string
     */
    public $name;
    
    /**
     * Activity description
     * 
     * @var string
     */
    public $description;
    
    /**
     * Activity type, is one of them: content, communication, interactivity
     * 
     * @var string
     */
    public $type;
    
    /**
     * 
     * Time when the activity starts
     * The value is an Unixtime
     * 
     * @var int
     */
    public $starttime;
    
    /**
     * 
     * Time when the activity ends
     * The value is an Unixtime
     * 
     * @var int
     */
    public $endtime;
    
    /**
     * 
     * Activity duration. How long the activity, in seconds.
     * The value is in seconds
     * 
     * @var int
     */
    
    public $limittime;
    
    /**
     * 
     * Numbers of attempts
     * 
     * @var int
     */
    public $attempts;
    
    /**
     * 
     * True if the activity is open all the time; false in other case
     * 
     * @var bool
     */
    public $keepopen;
    
    /**
     * 
     * Zone identify
     * @var string
     */
    public $zone;
    
    /**
     * 
     * World identify
     * @var string
     */
    public $world;
    
    /**
     * 
     * List of messengers
     * @var array
     */
    public $messenger = array();

    /**
     * 
     * Construct
     */
    public function __construct() {
        parent::__construct();

        // The namespace that describe the class
        $this->TargetNamespace = new SpecificationNamespace('vw3d', 'http://www.vw3d.org/0.1/messenger');
        $this->Resources = array('zone', 'world', 'messenger');
 
    }    
}
