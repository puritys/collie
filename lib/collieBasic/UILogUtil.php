<?php

class UILogUtil {

    static function screenshot($url) {

        return <<<HTML

        <div class="screen-image-wrap"><a href="$url"><img src="$url" class="screen-image img-rounded" ></a></div>
HTML;
    }

    static function testResult($total, $passed, $failed) {
        return <<<HTML
        <div class="test-result bs-example bs-label" data-label="Test Result">
            <table class="table">
                <tr>
                    <th>Total Case</th><th>Passed Number</th><th>Failed Number</th>
                </tr>
                <tr>
                    <td>$total</td><td class="passed-col">$passed</td><td class="failed-col">$failed</td>
                </tr>

            </table>
        </div>
HTML;
    }

    static function testReport($testResult) {
        $report = "";
        if ($testResult['failed'] > 0) {
            $failCase = "";
            foreach ($testResult['failedLog'] as $actualValue) {
                $failCase .= <<<HTML
                    <tr>
                        <td>xx</td><td class="passed-col"></td><td class="failed-col">$actualValue</td><td>xx</td>
                    </tr>

HTML;
            }
            $report .= <<<HTML
            <div class="test-report bs-example bs-label" data-label="Failed Test Report">
                <div>
                <p>Total Failed Test Number is ${testResult['failed']}.</p>
                <table class="table">
                    <tr>
                        <th>Test Name</th><th>Expect Value</th><th>Actual Value</th><th>Reason</th>
                    </tr>
                    $failCase
                </table>
                </div>
            </div>
HTML;
        }

        return $report;
    }

}
