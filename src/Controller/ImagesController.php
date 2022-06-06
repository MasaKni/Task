<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Images Controller
 *
 * @property \App\Model\Table\ImagesTable $Images
 * @method \App\Model\Entity\Image[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ImagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $key = $this -> request->getQuery('key');
        
   
        if ($key) {
            $value=explode("x", $key);
            $width=str_replace(' ', '', $value[0]);
            $height=str_replace(' ', '', $value[1]);
            $x=1;
            if (is_numeric($height)&& is_numeric($width)) {
                $query= $this->Images->find('all')
                                     ->where(['And'=>['Images.width like'=>$width,'Images.height like'=>$height]]);
            } else {
                $query = $this->Images;
                $this->Flash->error(__('Please Enter a numeric value widthXheight'));
            }
        } else {
            $query = $this->Images;
        }
  
        $images = $this->paginate($query);
        $this->set(compact('images'));
    }

    /**
     * View method
     *
     * @param string|null $id Image id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $image = $this->Images->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('image'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $image = $this->Images->newEmptyEntity();
        if ($this->request->is('post')) {
            $image = $this->Images->patchEntity($image, $this->request->getData());
           
            if (!$image->getErrors) {
                $Path = $this->request->getData('file_input');
                $width = $this->request->getData('width');
                $height = $this->request->getData('height');
                $type  = $Path->getClientMediaType();
                if ($type== 'image/jpeg' || $type == 'image/jpg' || $type == 'image/png') {
                    $name  = $Path->getClientFilename();
                    // $image_info = getimagesize($Path);
                    // echo ($image_info);
                    // exit();
                    // if ($image_info[0]> $width ||$image_info[1]> $height)
                    // {}
                    if (!is_dir(WWW_ROOT.'img')) {
                        mkdir(WWW_ROOT.'img', 0775);
                    }
                    
                    $targetPath = WWW_ROOT.'img'.DS.DS.$name;
    
                    if ($name) {
                        $Path->moveTo($targetPath);
                    }
                    
                    $image->file_input= $name;
                } else {
                    $this->Flash->error(__('Wrong type of file uploaded.'));
                }
            }
            if ($this->Images->save($image)) {
                $this->Flash->success(__('The image has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The image could not be saved. Please, try again.'));
        }
        $this->set(compact('image'));
    }
    /**
     * Edit method
     *
     * @param string|null $id Image id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $image = $this->Images->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $image = $this->Images->patchEntity($image, $this->request->getData());
            if ($this->Images->save($image)) {
                $this->Flash->success(__('The image has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The image could not be saved. Please, try again.'));
        }
        $this->set(compact('image'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Image id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $image = $this->Images->get($id);
        if ($this->Images->delete($image)) {
            $this->Flash->success(__('The image has been deleted.'));
        } else {
            $this->Flash->error(__('The image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
