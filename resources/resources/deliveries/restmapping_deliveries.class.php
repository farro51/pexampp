<?php
/*
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
 * Class to manage the segments resources mapping
 *
 * @author Cristina Madrigal <malevilema@gmail.com>
 * @version 0.1
 */
class RestMapping_Deliveries extends RestMapping {
    
    /**
     * 
     * Construct
     * @param object or array $data
     */
    public function __construct($data) {
        parent::__construct($data, "delivery", "deliveries");
    }

}
