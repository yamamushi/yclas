<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Categories extends Api_Controller {


    /**
     * Handle GET requests.
     */
    public function action_index()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $output = array();

                $cats = new Model_Category();

                //make a search with q? param
                if (isset($this->_params['q']) AND strlen($this->_params['q']))
                {
                    $cats->where_open()
                        ->where('name', 'like', '%'.$this->_params['q'].'%')
                        ->or_where('description', 'like', '%'.$this->_params['q'].'%')
                        ->where_close();
                }

                //filter results by param, verify field exists and has a value and sort the results
                $cats->api_filter($this->_filter_params)->api_sort($this->_sort);

                //how many? used in header X-Total-Count
                $count = $cats->count_all();


                $cats = $cats->cached()->find_all();

                //as array
                foreach ($cats as $category)
                {
                    $cat = $category->as_array();
                    //$cat['parents']  = $category->get_parents_ids();
                    //$cat['siblings'] = $category->get_siblings_ids();
                    $cat['icon']     = $category->get_icon();

                    $output[$category->id_category] = $cat;
                }

                $this->rest_output($output,200,$count);
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
    }

    /**
     * Handle GET requests.
     */
    public function action_all()
    {
        try
        {
            if (is_numeric($this->request->param('id')))
            {
                $this->action_get();
            }
            else
            {
                $this->rest_output(Model_Category::get_as_array());
            }
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
    }

    public function action_get()
    {
        try
        {
            $result = array();
            if (is_numeric($id_category = $this->request->param('id')))
            {
                $category = new Model_Category($id_category);
                if ($category->loaded())
                {
                    $cat = $category->as_array();
                    $cat['parents']  = $category->get_parents_ids();
                    $cat['siblings'] = $category->get_siblings_ids();
                    $cat['customfields']   = Model_Field::get_by_category($category->id_category);
                    $cat['icon']     = $category->get_icon();

                    $this->rest_output($cat);
                }
                else
                    $this->_error(__('Category not found'),404);
            }
            else
                $this->_error(__('Category not found'),404);
           
        }
        catch (Kohana_HTTP_Exception $khe)
        {
            $this->_error($khe);
            return;
        }
       
    }

} // END