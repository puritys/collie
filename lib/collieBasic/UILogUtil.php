<?php

class UILogUtil {

    static function screenshot($url) {

        return <<<HTML

        <div class="screen-image-wrap"><a href="$url" target="_blank"><img src="$url" class="screen-image img-rounded" ></a></div>
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
            foreach ($testResult['result'] as $result) {
                if ($result['passed'] == false) {
                    $actualValue = htmlspecialchars($result['actual']);
                    $failCase .= <<<HTML
                    <tr>
                        <td><div class="glyphicon glyphicon-warning-sign"></div></td><td>${result['expect']}</td><td><div class="actual-value">$actualValue</div></td><td class="failed-col">${result['reason']}</td>
                    </tr>

HTML;
                }
            }
            $report .= <<<HTML
            <div class="test-report">
                <div class="title">
                    <span class="glyphicon glyphicon-warning-sign"></span>Failed Test Report (${testResult['failed']})
                </div>
                <table class="table test-report-table">
                    <tr>
                        <th class="status-col">Status</th><th class="expected-col">Expect Value</th><th class="actual-col">Actual Value</th><th>Reason</th>
                    </tr>
                    $failCase
                </table>
                </div>
            </div>
HTML;
        }

        if ($testResult['passed'] > 0) {
            $failCase = "";
            foreach ($testResult['result'] as $result) {
                if ($result['passed'] == true) {
                    $actualValue = htmlspecialchars($result['actual']);
                    $failCase .= <<<HTML
                    <tr>
                        <td><div class="glyphicon glyphicon-ok"></div></td><td>${result['expect']}</td><td><div class="actual-value">$actualValue</div></td>
                    </tr>

HTML;
                }
            }
            $report .= <<<HTML
            <div class="test-report">
                <div class="title">
                    <span class="glyphicon glyphicon-ok"></span>Passed Test Report (${testResult['passed']})
                </div>
                <table class="table test-report-table">
                    <tr>
                        <th class="status-col">Status</th><th class="expected-col">Expect Value</th><th class="actual-col">Actual Value</th>
                    </tr>
                    $failCase
                </table>
                </div>
            </div>
HTML;
        }

        return $report;
    }

    static function startController($name, $type="") {
        if ($type == "test") {
            $desc = "Execute Test: ";
        } else {
            $desc = "Execute Controller:";
        }
        return <<<HTML
        <div class="controller-wrap">
            <div class="title bs-controller">$desc  $name</div>
HTML;
    }

    static function endController() {
        return <<<HTML
        </div>
HTML;

    }

    static function showLog($message, $level = 1) {
        return <<<HTML
        <p class="log-level-$level">$message</p>
HTML;

    }

}
