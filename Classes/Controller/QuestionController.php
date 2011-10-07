<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Jacco van der Post <jacco@typo3-webdesign.nl>, iD Webdesign
*  	
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Controller for the Question object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Tx_Jpfaq_Controller_QuestionController extends Tx_Extbase_MVC_Controller_ActionController {
	
	/**
	 * questionRepository
	 * 
	 * @var Tx_Jpfaq_Domain_Repository_QuestionRepository
	 */
	protected $questionRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->questionRepository = t3lib_div::makeInstance('Tx_Jpfaq_Domain_Repository_QuestionRepository');
                $this->categoryRepository = t3lib_div::makeInstance('Tx_Jpfaq_Domain_Repository_CategoryRepository');
                
                

		// stylesheets includes...
                $includes ='';
		foreach ($this->settings["includeCss"] as $cssFile) {

			$path ="";
			$mediatype ="all";

			if(isset($cssFile["path"])) {
				$path .=  str_replace ( "EXT:" , t3lib_extMgm::siteRelPath($this->request->getControllerExtensionKey()) , $cssFile["path"]);


				if(isset($cssFile["mediatype"])) {
					$mediatype = $cssFile["mediatype"];
				}

				$includes .= chr(13).'<link rel="stylesheet" type="text/css" href="'.$path.'" media="'.$mediatype.'" />';

			}


		}

		// javascript includes...
		foreach ($this->settings["includeJs"] as $jsFile) {

			$path ="";
			$mediatype ="all";

			if(isset($cssFile["path"])) {
				$path .=  str_replace ( "EXT:" , t3lib_extMgm::siteRelPath($this->request->getControllerExtensionKey()) , $jsFile["path"]);
				
                                // We only insert 1 js file at the moment, that is jquery library.
                                // But if t3jquery extension is installed we want to use that 
                                
                                // Check if extension t3jQuery is loaded
                                if (t3lib_extMgm::isLoaded('t3jquery')) {
                                    require_once(t3lib_extMgm::extPath('t3jquery') . 'class.tx_t3jquery.php');
                                }
                                // If t3jQuery is loaded and the custom Library has been created
                                if (T3JQUERY === true) {
                                    tx_t3jquery::addJqJS();
                                } else {
                                    // if none of the previous is true, include own library
                                    $includes .= chr(13).'<script src="'.$path.'" type="text/javascript"></script>';
                                }
                                
      
			}


		}


		// aan page header toevoegen
		$GLOBALS["TSFE"]->additionalHeaderData['jpFaq'] = $includes;
	}
	
	
		
	/**
	 * Displays all Questions
	 *
	 * @return string The rendered list view
	 */
	public function listAction() {
		// get selected category from Flexform
                $selectedCategory = intval($this->settings['flexform']['selectCategory']);
                // get all questions belonging to this category
                $flexformPid = intval($this->settings['flexform']['selectPid']);
                $questions = $this->questionRepository->getAllQuestionsForCategory($selectedCategory, $flexformPid);
		$this->view->assign('questions', $questions);
                $this->view->assign('category', $this->categoryRepository->getCategoryNameForCategoryUid($selectedCategory));
                $this->view->assign('categoryUid', $selectedCategory);
                #$category = $this->questionRepository->getCategory();
                #var_dump($this->categoryRepository->getCategoryNameForCategoryUid(2));
                #var_dump($selectedCategory);
                #var_dump($flexformPid);
	}
	
		
	/**
	 * Displays a single Question
	 *
	 * @param Tx_Jpfaq_Domain_Model_Question $question the Question to display
	 * @return string The rendered view
	 */
	public function showAction(Tx_Jpfaq_Domain_Model_Question $question) {
		$this->view->assign('question', $question);
	}
	
		
	/**
	 * Creates a new Question and forwards to the list action.
	 *
	 * @param Tx_Jpfaq_Domain_Model_Question $newQuestion a fresh Question object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Question
	 * @dontvalidate $newQuestion
	 */
	public function newAction(Tx_Jpfaq_Domain_Model_Question $newQuestion = NULL) {
		$this->view->assign('newQuestion', $newQuestion);
	}
	
		
	/**
	 * Creates a new Question and forwards to the list action.
	 *
	 * @param Tx_Jpfaq_Domain_Model_Question $newQuestion a fresh Question object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Jpfaq_Domain_Model_Question $newQuestion) {
		$this->questionRepository->add($newQuestion);
		$this->flashMessageContainer->add('Your new Question was created.');
		$this->redirect('list');
	}
	
		
	
	/**
	 * Updates an existing Question and forwards to the index action afterwards.
	 *
	 * @param Tx_Jpfaq_Domain_Model_Question $question the Question to display
	 * @return string A form to edit a Question 
	 */
	public function editAction(Tx_Jpfaq_Domain_Model_Question $question) {
		$this->view->assign('question', $question);
	}
	
		

	/**
	 * Updates an existing Question and forwards to the list action afterwards.
	 *
	 * @param Tx_Jpfaq_Domain_Model_Question $question the Question to display
	 */
	public function updateAction(Tx_Jpfaq_Domain_Model_Question $question) {
		$this->questionRepository->update($question);
		$this->flashMessageContainer->add('Your Question was updated.');
		$this->redirect('list');
	}
	
		
			/**
	 * Deletes an existing Question
	 *
	 * @param Tx_Jpfaq_Domain_Model_Question $question the Question to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Jpfaq_Domain_Model_Question $question) {
		$this->questionRepository->remove($question);
		$this->flashMessageContainer->add('Your Question was removed.');
		$this->redirect('list');
	}
	
}
?>