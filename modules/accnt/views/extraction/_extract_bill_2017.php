<?php

use app\models\Document;

echo $this->render('_extract_bill_lines_2017', ['model' => $model->getDocumentLines(), 'order' => $model]);