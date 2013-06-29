BOOTSTRAP = ../public/css/bootstrap.css
BOOTSTRAP_MIN = ../public/css/bootstrap.min.css
BOOTSTRAP_LESS = ./bootstrap.less
STYLE_MIN = ../public/css/style.min.css
STYLE_LESS = ./style.less
BOOTSTRAP_RESPONSIVE = ../public/css/bootstrap-responsive.css
BOOTSTRAP_RESPONSIVE_MIN = ../public/css/bootstrap-responsive.min.css
BOOTSTRAP_RESPONSIVE_LESS = ./responsive.less
DATE=$(shell date +%I:%M%p)
CHECK=\033[32m✔\033[39m
HR=\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#


#
# BUILD DOCS
#

build:
	@echo "\n${HR}"
	@echo "Building Bootstrap..."
	@echo "${HR}\n"
	@echo "Running LessC...                            ${CHECK} Done"
# @rm ./bootstrap -r
# @mkdir ./bootstrap/
# @mkdir ./bootstrap/css/
	@lessc ${BOOTSTRAP_LESS} > ${BOOTSTRAP}
	@lessc ${BOOTSTRAP_RESPONSIVE_LESS} > ${BOOTSTRAP_RESPONSIVE}
	@lessc ${BOOTSTRAP_LESS} --compress > ${BOOTSTRAP_MIN}
	@lessc ${STYLE_LESS} --compress > ${STYLE_MIN}
	@lessc ${BOOTSTRAP_RESPONSIVE_LESS} --compress > ${BOOTSTRAP_RESPONSIVE_MIN}
	@echo "Compiling CSS...                            ${CHECK} Done"
# @cp ${BOOTSTRAP_MIN} ../public/css
# @cp ${BOOTSTRAP_RESPONSIVE_MIN} ../public/css
# @cp ${STYLE_MIN} ../public/css
# @echo "Copy to public/css directory...             ${CHECK} Done"
	@echo "Thanks for using Bootstrap,"
	@echo "<3 @mdo and @fat\n"
