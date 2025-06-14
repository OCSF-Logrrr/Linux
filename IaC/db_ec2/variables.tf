variable "project_name" {
  type        = string
  default     = "WHS-Logrrr"
  description = "Project name"
}

###########################################################################################
############################### DB Server EC2 #############################################
###########################################################################################

variable "vpc_id" {
  type        = string
  description = "vpc_id"
}

variable "public_subnet_id_02" {
  type        = string
  description = "public_subnet_id_02"
}

variable "key_name" {
  type        = string
  default     = "whs_ocsf_logrrr"
  description = "key_name"
}