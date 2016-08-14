<?php
namespace Akaramires\FullContact;


/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Illuminate\Support\Facades\Facade;

/**
 * This class provides a Facade for the FullContactServiceProvider
 *
 * @package  Services\FullContact
 * @author   Josh Pollock <Josh@CalderaWP.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache
 */
class FullContactFacade extends Facade {

	/**
	 * {@inheritDocs}
	 */
	protected static function getFacadeAccessor() { return 'fullcontact'; }

}
