<?php

defined('_JEXEC') or die;

jimport('joomla.environment.browser');

abstract class ModVCHelper {
	
	private static $datediff = 30;
		
	private static function get_ip_address() {
		$server = JFactory::getApplication()->input->server;
		$ip_address = $server->get('REMOTE_ADDR');
		return ip2long($ip_address);
	}
	
	private static function get_user_agent() {
		$user_agent = JBrowser::getInstance()->getAgentString();
		return $user_agent;
	}
	
	public static function get_num_of_ip_addresses() {
		$value = self::get_ip_address();
		$user_agent = self::get_user_agent();
		
		$handle = JFactory::getDbo();
		
		$query = $handle->getQuery(true);
		$query->select($handle->quoteName('ip_address'));
		$query->from($handle->quoteName('#__vc'));
		$query->where($handle->quoteName('ip_address')."=".$handle->quote($value)." and ".$handle->quoteName('user_agent')."=".$handle->quote($user_agent));
		
		$handle->setQuery($query);
		
		try {
			$results = $handle->loadObjectList();
		} catch (RuntimeException $e) {
			JError::raiseError(500, $e->getMessage());
		}
		if ($results) {
		} else {
			self::write_ip_address_and_user_agent_into_db($value, $user_agent);
			self::set_counter();
		}
		return self::get_counter();	
	}
	
	/** Deprecated, use get_counter() instead;
	 * 
	 * */
	private static function count_rows() {
		$handle = JFactory::getDbo();
		
		$query = $handle->getQuery(true);
		
		$query->select('COUNT(*)');
		$query->from($handle->quoteName('#__vc'));
		
		$handle->setQuery($query);
		try {
			$count = $handle->loadResult();
		} catch (RuntimeException $e) {
			JError::raiseError(500, $e->getMessage());
		}
		return $count;
	}
	
	private static function set_counter() {
		$handle = JFactory::getDbo();
		
		$query = $handle->getQuery(true);
		
		$fields = array($handle->quoteName('access_counter').' = '.$handle->quoteName('access_counter').' + 1');
		$conditions = array($handle->quoteName('id').' = 1');
		$query->update($handle->quoteName('#__counter'))->set($fields)->where($conditions);

		$handle->setQuery($query);

		try {
			$handle->execute();
		} catch (RuntimeException $e) {
			JError::raiseError(500, $e->getMessage());
			return false;
		}
	}
	
	private static function get_counter() {
		$handle = JFactory::getDbo();
		
		$query = $handle->getQuery(true);
		$query->select($handle->quoteName('access_counter'));
		$query->from($handle->quoteName('#__counter'));
		$query->where($handle->quoteName('id').' = '.$handle->quote(1));
		$handle->setQuery($query);
		try {
			$count = $handle->loadResult();
		} catch (RuntimeException $e) {
			JError::raiseError(500, $e->getMessage());
		}
		return $count;
	}
	
	public static function delete_records() {
		$handle = JFactory::getDbo();
		$query = $handle->getQuery(true);
		$query->delete($handle->quoteName('#__vc'));
		$query->where('DATEDIFF('.$handle->quoteName('date_of_access').' , '.'SYSDATE()'.')'.' >= '.$handle->quote(self::$datediff));
		$handle->setQuery($query);
		try {
			$handle->execute();
		} catch (RuntimeException $e) {
			JError::raiseError(500, $e->getMessage());
			return false;
		}
	}
	
	private static function write_ip_address_and_user_agent_into_db($ip_address, $user_agent) {
		$handle = JFactory::getDbo();
		
		$query = $handle->getQuery(true);
		$columns = array('ip_address', 'user_agent', 'date_of_access');
		$values = array($handle->quote($ip_address), $handle->quote($user_agent), 'SYSDATE()');
		
		$query->insert($handle->quoteName('#__vc'));
		$query->columns($handle->quoteName($columns));
		$query->values(implode(",", $values));
		
		$handle->setQuery($query);
		try {
			$handle->execute();
		} catch (RuntimeException $e) {
			JError::raiseError(500, $e->getMessage());
			return false;
		}
	}
}
