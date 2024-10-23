<?php
        trait GoBack{
            public function mylocalprint(){
                echo <<<GOBACK
                    <p></p>
                    <p></p>
                        <a href="/samelan" class="btn btn-primary">Go Back</a>
                    GOBACK;
            }
        }