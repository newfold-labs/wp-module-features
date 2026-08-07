#!/usr/bin/env ruby
# frozen_string_literal: true

# Verifies workflows against the organization's workflow audit rules (top-level permissions,
# preamble comments, job-level permissions placement, timeouts). See sibling newfold-labs
# repository file `.workflow_audit_instructions.md`.

require 'yaml'

ROOT = File.expand_path('..', __dir__)
WORKFLOW_DIR = File.join(ROOT, '.github/workflows')

MAGIC_COMMENT_1 = '# Disable permissions for all available scopes by default.'
MAGIC_COMMENT_2 = '# Any needed permissions should be configured at the job level.'

abort("No workflows under #{WORKFLOW_DIR}") unless Dir.exist?(WORKFLOW_DIR)

paths = Dir[File.join(WORKFLOW_DIR, '*.{yml,yaml}')].sort
abort('No workflow files found') if paths.empty?

errors = []

paths.each do |path|
  rel = path.sub(%r{\A#{Regexp.escape(ROOT)}/}, '')
  raw = File.read(path).delete("\r")

  prefix_before_jobs, = raw.split(/^jobs:\s*$/m, 2)

  pre = prefix_before_jobs.to_s.rstrip
  unless pre.include?(MAGIC_COMMENT_1) && pre.include?(MAGIC_COMMENT_2) && pre.match?(/\npermissions:\s*\{\}\s*\z/)
    errors << "#{rel}: top-level block must include the audit comment lines and end with permissions: {} immediately before jobs:"
  end

  doc = YAML.load_file(path)
  jobs = doc && doc['jobs']
  unless jobs.is_a?(Hash)
    errors << "#{rel}: invalid or missing jobs: mapping"
    next
  end

  jobs.each do |job_id, job_def|
    job_label = "#{rel} :: #{job_id}"
    unless job_def.is_a?(Hash)
      errors << "#{job_label}: job definition must be a mapping"
      next
    end

    unless job_def.key?('permissions')
      errors << "#{job_label}: missing job-level permissions"
    end

    unless job_def.key?('timeout-minutes')
      errors << "#{job_label}: missing timeout-minutes"
    end

    keys = job_def.keys.map(&:to_s)

    if keys.include?('uses')
      u = keys.index('uses')
      p = keys.index('permissions')
      if p.nil? || p != u + 1
        errors << "#{job_label}: permissions must be the YAML key immediately after uses (reusable workflow callers)"
      end
    end

    next unless keys.include?('runs-on')

    r = keys.index('runs-on')
    p = keys.index('permissions')
    if p.nil? || p != r + 1
      errors << "#{job_label}: permissions must be the YAML key immediately after runs-on"
    end
  end
end

if errors.any?
  warn errors.join("\n")
  exit 1
end

puts "Workflow compliance OK (#{paths.size} file(s))"
exit 0
